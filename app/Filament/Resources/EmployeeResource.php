<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms\Components\FileUpload;
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

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $slug = 'pegawai';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Data Pegawai';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $label = 'Data Pegawai';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Pegawai')
                    ->required(),
                TextInput::make('license_number')
                    ->label('Nomor Lisensi Mengemudi')
                    ->unique(ignoreRecord: true)
                    ->mask('9999-9999-999999')
                    ->required(),
                TextInput::make('phone_number')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->mask('9999-9999-99999')
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                    ])
                    ->searchable()
                    ->required(),
                FileUpload::make('photo')
                    ->label('Foto Pegawai')
                    ->image()
                    ->preserveFilenames()
                    ->maxSize(2000)
                    ->hint( __('Max. 2MB'))
                    ->disk('public')
                    ->directory('driver_photos')
                    ->visibility('public')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pegawai')
                    ->formatStateUsing(function ($state, $record) {
                        if (filter_var($record->photo, FILTER_VALIDATE_URL)) {
                            $photoUrl = $record->photo;
                        } else {
                            $photoUrl = asset('storage/' . $record->photo);
                        }
                        return "<span style='display: flex; align-items: center;'><img src='{$photoUrl}' alt='Photo' style='width: 40px; height: 40px; border-radius: 8px; margin-right: 8px;'>{$state}</span>";
                    })->html()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('license_number')
                    ->label('Nomor Lisensi Mengemudi')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->label('Nomor Telepon')
                    ->formatStateUsing(function ($state) {
                        return preg_replace('/(\d{4})(\d{4})(\d{4,5})/', '$1-$2-$3', $state);
                    })
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(function (Employee $record): string {
                        return ucwords(strtolower(match ($record->status) {
                            'active' => 'aktif',
                            'inactive' => 'tidak aktif',
                        }));
                    })
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'aktif' => 'success',
                        'tidak aktif' => 'danger',
                    }),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                    ])
                    ->label('Status'),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
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
        return ['name'];
    }
}
