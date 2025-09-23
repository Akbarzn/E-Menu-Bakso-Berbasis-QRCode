<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionHistoryResource\Pages;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Forms\Form;          // <--- gunakan Filament\Forms\Form
use Filament\Tables\Table;        // <--- gunakan Filament\Tables\Table
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class TransactionHistoryResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Riwayat Transaksi';
    protected static ?string $navigationGroup = 'Transaksi';

    // Form must use Filament\Forms\Form (same as parent signature)
    public static function form(Form $form): Form
    {
        // riwayat biasanya read-only -> kosongkan atau minimal fields
        return $form->schema([
            // kalau mau view detail di modal, isi schema sesuai kebutuhan
        ]);
    }

    // Table must use Filament\Tables\Table (same as parent signature)
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_transaksi')->label('Kode')->searchable()->sortable(),
                TextColumn::make('nama_pelanggan')->label('Pelanggan')->sortable(),
                TextColumn::make('nomor_meja')->label('Meja'),
                TextColumn::make('metode_pembayaran')->label('Metode'),
                TextColumn::make('total_harga')->label('Total')->money('IDR', true),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'menunggu',
                        'primary' => 'diproses',
                        'success' => 'selesai',
                        'danger'  => 'batal',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // biasanya riwayat readonly -> kosongkan atau tambahkan view action
            ])
            ->bulkActions([]);
    }

    // Filter the resource so it only shows finished / cancelled transactions
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('status', ['selesai', 'batal']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactionHistories::route('/'),
        ];
    }
}
