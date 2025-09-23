<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
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
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                 Select::make('category_id')->relationship('category', 'nama_kategori')->required(),
            TextInput::make('nama_menu')->required(),
            Textarea::make('deskripsi')->nullable(),
            TextInput::make('harga')->numeric()->required(),
            TextInput::make('stok')->numeric()->required(),
            FileUpload::make('image')->image(),
            Toggle::make('status')->label('Tersedia')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                   TextColumn::make('id')->sortable(),
            TextColumn::make('nama_menu')->searchable(),
            TextColumn::make('harga')->money('IDR', true),
            // TextColumn::make('stok'),
            TextColumn::make('category.nama_kategori')->label('Kategori'),
            TextColumn::make('status')->badge(),
            ])
            ->filters([
                TernaryFilter::make('status')
                ->label('Tampilkan yang tersedia')
                ->trueLabel('Tersedia')
                ->falseLabel('Tidak Tersedia')
                ->default(null),
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
