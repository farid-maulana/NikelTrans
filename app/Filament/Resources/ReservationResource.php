<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use App\Models\Vehicle;
use Filament\Forms\Components\DatePicker;
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
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;
    protected static ?string $slug = 'pemesanan';
    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $navigationLabel = 'Pemesanan Kendaraan';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Pemesanan';
    protected static ?string $label = 'Pemesanan Kendaraan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('employee_id')
                    ->label('Pegawai/Driver')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->required(),
                Select::make('vehicle_type')
                    ->label('Jenis Kendaraan')
                    ->options([
                        'personnel' => 'Personel',
                        'cargo' => 'Kargo',
                    ])
                    ->reactive()
                    ->afterStateUpdated(function ($set) {
                        $set('vehicle_id', null);
                    })
                    ->searchable()
                    ->required(),
                Select::make('vehicle_id')
                    ->label('Kendaraan')
                    ->disabled(fn ($get) => is_null($get('vehicle_type')))
                    ->relationship('vehicle', 'license_plate', function ($query, $get) {
                        return $query->where('type', $get('vehicle_type'));
                    })
                    ->reactive()
                    ->afterStateUpdated(function ($set, $record) {
                        if ($vehicle = $record->vehicle) {
                            $set('license_plate', $vehicle->license_plate);  // Set license_plate ketika vehicle_id dipilih
                        }
                    })
                    ->searchable()
                    ->required(),
                TextInput::make('license_plate')
                    ->label('Nomor Plat Kendaraan')
                    ->readOnly(),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Pegawai/Driver')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vehicle.license_plate')
                    ->label('Kendaraan')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date(),
                TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date(),
                TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(function (Reservation $record): string {
                        return ucwords(strtolower(match ($record->status) {
                            'pending' => 'menunggu',
                            'approved' => 'disetujui',
                            'on process' => 'dalam proses',
                            'rejected' => 'ditolak',
                        }));
                    })
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'menunggu' => 'gray',
                        'dalam proses' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'on process' => 'Dalam Proses',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
            'view' => Pages\ViewReservation::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['employee', 'user']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['employee.name', 'user.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->employee) {
            $details['Employee'] = $record->employee->name;
        }

        if ($record->user) {
            $details['User'] = $record->user->name;
        }

        return $details;
    }
}
