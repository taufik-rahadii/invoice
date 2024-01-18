<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerResource\Pages;
use App\Filament\Resources\SellerResource\RelationManagers;
use App\Models\Organization;
use App\Models\Role;
use Filament\Forms;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SellerResource extends Resource
{
    protected static ?string $slug = 'sellers';

    protected static ?string $model = User::class;

    protected static bool $isGloballySearchable = false;
    protected static ?string $recordTitleAttribute = 'Seller';
    protected static ?string $navigationGroup = 'Reseller';
    protected static ?string $label = 'Seller';
    protected static ?string $navigationLabel = 'Seller';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('role', function ($query) {
            $query->where('code', 'seller');
        });
        ;
    }

    public static function form(Form $form): Form
    {
        $organizationOptions = Organization::query()
            ->first();
        $roleOptions = Role::query()
            ->where('code', 'seller')
            ->first();


        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->required()
                        ->rules(['min:6']),
                    // Forms\Components\TextInput::make('role_id')
                    //     ->readonly()
                    //     ->default('ed9ae8e7-93ce-466e-a488-58735d7efc84'),
                    Forms\Components\Select::make('role_id')
                        ->options(Role::where('id', 'ed9ae8e7-93ce-466e-a488-58735d7efc84')->pluck('name', 'id'))
                        ->default('ed9ae8e7-93ce-466e-a488-58735d7efc84')
                        ->label('Role'),
                    Forms\Components\Select::make('organization_id')
                        ->label('Brand / Perusahaan')
                        ->options(Organization::where('id', '9b177f37-bf19-49f1-8fce-5373ebd013fc')->pluck('name', 'id'))
                        ->default('9b177f37-bf19-49f1-8fce-5373ebd013fc'),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('organization.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
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
            'index' => Pages\ListSellers::route('/'),
            'create' => Pages\CreateSeller::route('/create'),
            'edit' => Pages\EditSeller::route('/{record}/edit'),
        ];
    }
}
