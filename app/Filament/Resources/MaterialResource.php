<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\Material;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Nuwave\Lighthouse\Federation\Types\FieldSet;

class MaterialResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $slug = 'materials';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Forms\Components\Section::make('أسعار المواد والعملات')->schema([
                  Forms\Components\Wizard::make([
                      Forms\Components\Wizard\Step::make('الأسعار في إدلب')->schema([
                          Forms\Components\Fieldset::make('اسعار الذهب')->schema([
                              Forms\Components\TextInput::make('gold.idlib.gold24.sale')->label('سعر مبيع الذهب عيار 24'),
                              Forms\Components\TextInput::make('gold.idlib.gold24.bay')->label('سعر شراء الذهب عيار 24'),
                              Forms\Components\TextInput::make('gold.idlib.gold21.sale')->label('سعر مبيع الذهب عيار  21 '),
                              Forms\Components\TextInput::make('gold.idlib.gold21.bay')->label('سعر شراء الذهب عيار 21  '),
                              Forms\Components\TextInput::make('gold.idlib.gold18.sale')->label('سعر مبيع الذهب عيار 18  '),
                              Forms\Components\TextInput::make('gold.idlib.gold18.bay')->label('سعر شراء الذهب عيار 18  '),
                              Forms\Components\TextInput::make('gold.idlib.sliver.bay')->label('سعر شراء الفضة  '),
                              Forms\Components\TextInput::make('gold.idlib.sliver.sale')->label('سعر مبيع الفضة  '),
                          ]),
                          Forms\Components\Fieldset::make('سعر العملات')->schema([
                              Forms\Components\TextInput::make('dollar.idlib.usd.sale')->label('سعر الدولار بالتركي مبيع'),
                              Forms\Components\TextInput::make('dollar.idlib.usd.bay')->label('سعر الدولار بالتركي شراء'),

                              Forms\Components\TextInput::make('dollar.idlib.eur.sale')->label(' سعر اليورو بالدولار مبيع'),
                              Forms\Components\TextInput::make('dollar.idlib.eur.bay')->label('سعر اليورو بالدولار شراء'),

                              Forms\Components\TextInput::make('dollar.idlib.syr.sale')->label('سعر الدولار بالسوري مبيع'),
                              Forms\Components\TextInput::make('dollar.idlib.syr.bay')->label('سعر الدولار بالسوري شراء'),
                          ]),
                          /*
                           *
                          material_izaz
                           * */
                          Forms\Components\Fieldset::make('أسعار المواد')->schema([
                              Forms\Components\Repeater::make('material_idlib')->schema([
                                  Forms\Components\TextInput::make('name')->label('اسم المادة'),
                                  Forms\Components\TextInput::make('bay')->label('سعر الشراء'),
                                  Forms\Components\TextInput::make('sale')->label('سعر المبيع'),

                              ])->columnSpan(2),
                          ])->columnSpan(2),

                      ]),
                      Forms\Components\Wizard\Step::make('الأسعار في إعزاز')->schema([
                          Forms\Components\Fieldset::make('اسعار الذهب')->schema([
                              Forms\Components\TextInput::make('gold.izaz.gold24.sale')->label('سعر مبيع الذهب عيار 24'),
                              Forms\Components\TextInput::make('gold.izaz.gold24.bay')->label('سعر شراء الذهب عيار 24'),
                              Forms\Components\TextInput::make('gold.izaz.gold21.sale')->label('سعر مبيع الذهب عيار  21 '),
                              Forms\Components\TextInput::make('gold.izaz.gold21.bay')->label('سعر شراء الذهب عيار 21  '),
                              Forms\Components\TextInput::make('gold.izaz.gold18.sale')->label('سعر مبيع الذهب عيار 18  '),
                              Forms\Components\TextInput::make('gold.izaz.gold18.bay')->label('سعر شراء الذهب عيار 18  '),
                              Forms\Components\TextInput::make('gold.izaz.sliver.bay')->label('سعر شراء الفضة  '),
                              Forms\Components\TextInput::make('gold.izaz.sliver.sale')->label('سعر مبيع الفضة  '),
                          ]),
                          Forms\Components\Fieldset::make('سعر العملات')->schema([
                              Forms\Components\TextInput::make('dollar.izaz.usd.sale')->label('سعر الدولار بالتركي مبيع'),
                              Forms\Components\TextInput::make('dollar.izaz.usd.bay')->label('سعر الدولار بالتركي شراء'),

                              Forms\Components\TextInput::make('dollar.izaz.eur.sale')->label(' سعر اليورو بالدولار مبيع'),
                              Forms\Components\TextInput::make('dollar.izaz.eur.bay')->label('سعر اليورو بالدولار شراء'),

                              Forms\Components\TextInput::make('dollar.izaz.syr.sale')->label('سعر الدولار بالسوري مبيع'),
                              Forms\Components\TextInput::make('dollar.izaz.syr.bay')->label('سعر الدولار بالسوري شراء'),
                          ]),
                          /*
                           *
                          material_izaz
                           * */
                          Forms\Components\Fieldset::make('أسعار المواد')->schema([
                              Forms\Components\Repeater::make('material_izaz')->schema([
                                  Forms\Components\TextInput::make('name')->label('اسم المادة'),
                                  Forms\Components\TextInput::make('bay')->label('سعر الشراء'),
                                  Forms\Components\TextInput::make('sale')->label('سعر المبيع'),

                              ])->columnSpan(2),
                          ]),

                      ])
                  ])->skippable(),
                  ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}
