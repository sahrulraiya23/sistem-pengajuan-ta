<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThesisResource\Pages;
use App\Models\Thesis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ThesisResource extends Resource
{
    protected static ?string $model = Thesis::class;

    // Ikon di sidebar admin
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Arsip Tesis';
    protected static ?string $modelLabel = 'Arsip Tesis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Select::make('type')
                    ->label('Jenis Karya')
                    ->options([
                        'skripsi' => 'Skripsi',
                        'tesis' => 'Tesis',
                        'disertasi' => 'Disertasi',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('author')
                    ->label('Penulis')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('program_study')
                    ->label('Program Studi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('year')
                    ->label('Tahun')
                    ->numeric()
                    ->required()
                    ->minValue(1900)
                    ->maxValue(date('Y') + 1),
                Forms\Components\Textarea::make('abstract')
                    ->label('Abstrak')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('author')
                    ->label('Penulis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->colors([
                        'primary' => 'skripsi',
                        'warning' => 'tesis',
                        'success' => 'disertasi',
                    ]),
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('program_study')
                    ->label('Prodi'),
            ])
            ->filters([
                // Filter berdasarkan tipe bisa ditambahkan disini
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTheses::route('/'),
            'create' => Pages\CreateThesis::route('/create'),
            'edit' => Pages\EditThesis::route('/{record}/edit'),
        ];
    }
}