<?php

namespace App\Filament\Resources;

use App\Exports\StudentsExport;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Tables\Actions\BulkAction;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Filters\Filter;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Academic Management';

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->autofocus(),
                TextInput::make('email')
                    ->unique()
                    ->required(),
                Select::make('class_id')
                    ->relationship(name: 'class', titleAttribute: 'name')
                    ->live(),
                Select::make('section_id')
                    ->options(function (Get $get) {
                        $classId = $get('class_id');
                        if ($classId) {
                            return Section::query()
                                ->where('class_id', $classId)
                                ->pluck('name', 'id')
                                ->toArray();
                        }
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('class.name')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('section.name')
                    ->badge()
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                Filter::make('class-section-filter')
                    ->form([
                        Select::make('class_id')
                            ->label('Filter By Class')
                            ->placeholder('Select a Class')
                            ->options(Classes::query()->pluck('name', 'id')->toArray()),

                        Select::make('section_id')
                            ->label('Filter By Section')
                            ->placeholder('Select a Section')
                            ->options(function (Get $get) {
                                $classId = $get('class_id');
                                if ($classId) {
                                    return Section::query()
                                        ->where('class_id', $classId)
                                        ->pluck('name', 'id')
                                        ->toArray();
                                }
                            })
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['class_id'], function ($query, $classId) {
                            return $query->where('class_id', $classId);
                        })->when($data['section_id'], function ($query, $sectionId) {
                            return $query->where('section_id', $sectionId);
                        });
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('export')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            return Excel::download(new StudentsExport($records), 'students.xlsx');
                        })
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
