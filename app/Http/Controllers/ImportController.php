<?php

namespace App\Http\Controllers;

use App\Imports\CityImport;
use App\Imports\ColorImport;
use App\Imports\MediaImport;
use App\Imports\PhoneImport;
use App\Imports\ProductImport;
use App\Imports\ServicesImport;
use App\Imports\UserImport;
use App\Imports\ViewsImport;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'type'=>'required'
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        switch ($request->type){
            case 'users':
                $excelImport = new UserImport();
                break;
            case 'cities':
                $excelImport = new CityImport();
                break;
            case 'products':
                $excelImport = new ProductImport();
                break;
            case 'colors':
                $excelImport = new ColorImport();
                break;
            case 'media':
                $excelImport = new MediaImport();
                break;
            case 'seens':
                $excelImport = new ViewsImport();
                break;
            case 'service':
                $excelImport = new ServicesImport();
                break;
            case 'phone':
                $excelImport = new PhoneImport();
                break;
            default:
                $excelImport = new UserImport();
                break;
        }




        $excelImport->import($filePath);

        return redirect()->back()->with('success', 'تم استيراد البيانات بنجاح.');

        return redirect()->back()->with('success', 'تم استيراد البيانات بنجاح.');
    }
}
