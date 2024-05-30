<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Models\Vehicle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;
    protected static ?string $slug = 'kendaraan';
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Data Kendaraan';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $label = 'Data Kendaraan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('license_plate')
                    ->label('Nomor Plat Kendaraan')
                    ->required(),
                TextInput::make('brand')
                    ->label('Merk Kendaraan')
                    ->required(),
                Select::make('type')
                    ->label('Jenis Kendaraan')
                    ->options([
                        'personnel' => 'Personnel',
                        'cargo' => 'Cargo',
                    ])
                    ->searchable()
                    ->required(),
                Select::make('ownership')
                    ->label('Status Kepemilikan')
                    ->options([
                        'owned' => 'Milik Perusahaan',
                        'rented' => 'Sewa',
                    ])
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('license_plate')
                    ->label('Plat Nomor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('brand')
                    ->label('Merk Kendaraan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Jenis Kendaraan')
                    ->getStateUsing(function (Vehicle $record): string {
                        return match ($record->type) {
                            'personnel' => 'Personel',
                            default => 'Kargo',
                        };
                    }),
                TextColumn::make('ownership')
                    ->label('Status Kepemilikan')
                    ->getStateUsing(function (Vehicle $record): string {
                        return $record->ownership === 'owned' ? 'Milik Perusahaan' : 'Sewa';
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(function (Vehicle $record): string {
                        return ucwords(strtolower(match ($record->status) {
                            'available' => 'tersedia',
                            'unavailable' => 'tidak tersedia',
                            'on maintenance' => 'dalam perbaikan',
                        }));
                    })
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'tersedia' => 'success',
                        'tidak tersedia' => 'danger',
                        'dalam perbaikan' => 'gray',
                    })
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('type')
                    ->options([
                        'personnel' => 'Personel',
                        'cargo' => 'Kargo',
                    ])
                    ->label('Jenis Kendaraan'),
                SelectFilter::make('ownership')
                    ->options([
                        'owned' => 'Milik Perusahaan',
                        'rented' => 'Sewa',
                    ])
                    ->label('Status Kepemilikan'),
                SelectFilter::make('status')
                    ->options([
                        'available' => 'Tersedia',
                        'unavailable' => 'Tidak Tersedia',
                        'on maintenance' => 'Sedang Dalam Perbaikan',
                    ])
                    ->label('Status Kendaraan'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['license_plate', 'brand'];
    }
}
