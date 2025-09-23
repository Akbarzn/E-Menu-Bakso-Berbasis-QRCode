<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Menu;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('kode_transaksi')
             ->default(function () {
        $lastId = \App\Models\Transaction::max('id') + 1;
        return 'TRX-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);
    })
                ->disabled()
                ->dehydrated(false)
                ->label('Kode Transaksi'),

            TextInput::make('nama_pelanggan')
                ->label('Nama Pelanggan')
                ->required(),
                
            TextInput::make('nomor_meja')
                ->label('Nomor Meja')
                ->required(),    

            Select::make('metode_pembayaran')
                ->options([
                    'manual' => 'Manual',
                    'midtrans' => 'Midtrans',
                ])
                ->required(),

            Select::make('status')
                ->options([
                    'menunggu' => 'Menunggu',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                ])
                ->default('diproses')
                ->required(),

            // Textarea::make('catatan')->nullable(),/

            Repeater::make('menuTransactions')
                ->relationship()
                ->schema([
                    Select::make('menu_id')
                        ->label('Menu')
                        ->relationship('menu', 'nama_menu')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('harga', Menu::find($state)?->harga ?? 0)),

                    TextInput::make('jumlah')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $get, callable $set){
                            $harga = (int) $get('harga');
                            $set('subtotal', $harga * (int) $state);
                        }),

                    TextInput::make('harga')
                        ->numeric()
                        ->disabled(),
                        // ->dehydrated(false)
                        // ->visible(false),

                    TextInput::make('subtotal')
                        ->disabled()
                        ->numeric(),    

                    // TextInput::make('catatan')->nullable(),
                ])
                ->columns(4)
                ->reactive()
                ->afterStateUpdated(function (callable $get, callable $set) {
                    $total = collect($get('menuTransactions'))
                    ->sum(fn ($item) => ((int) ($item['harga'] ?? 0)) * ((int) ($item['jumlah'] ?? 0)));
                    $set('total_harga', $total);
                }),

            TextInput::make('total_harga')
                ->prefix('Rp')
                ->numeric()
                ->disabled()
                ->dehydrated()
                ->required()
                ->label('Total Harga'),

            DateTimePicker::make('created_at')
                ->default(now())
                ->disabled()
                ->dehydrated(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_transaksi')->sortable(),
            Tables\Columns\TextColumn::make('nama_pelanggan')->sortable(),
            Tables\Columns\TextColumn::make('nomor_meja'),
            Tables\Columns\TextColumn::make('metode_pembayaran'),
            Tables\Columns\TextColumn::make('total_harga')->money('idr'),
            Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y H:i'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                ->visible(fn ($record) => $record->status === 'menunggu'),
                Action::make('konfirmasi')
                    ->label('Konfirmasi Pembayaran')
                    ->visible(fn ($record) => $record->status === 'menunggu')
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'selesai',
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $total = 0;

        if (isset($data['menuTransactions'])) {
            foreach ($data['menuTransactions'] as $item) {
                $menu = Menu::find($item['menu_id']);
                if ($menu) {
                    $total += ((int) $menu->harga) * ((int) $item['jumlah']);
                }
            }
        }

        $data['total_harga'] = $total;

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        return static::mutateFormDataBeforeCreate($data);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }


    public static function canViewAny():bool{
        return Auth::user()?->hasRole(['admin', 'super_admin']);
    }

    public static function canCreate():bool{
        return Auth::user()?->hasRole(['admin', 'super_admin']);
    }

    public static function canEdit(Model $record): bool{
        return Auth::user()?->hasRole('admin', 'super_admin');
    }

    public static function canDelete(Model $record): bool{
        return Auth::user()?->hasRole('super_admin');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder{
        return parent::getEloquentQuery()
        ->whereIn('status', ['menunggu', 'diproses']);
    }
}
