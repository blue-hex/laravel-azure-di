## Installation
First, you need to install the package via Composer. Run the following command in your terminal:

`composer require blue-hex/laravel-azure-di`

## Configuration
After installing the package, you may need to publish the configuration file. This can typically be done using the following Artisan command:

`php artisan vendor:publish --provider="BlueHex\AzureDI\AzureDIServiceProvider"`

This command will publish a configuration file named `azure-di.php` to your config directory. You should then add your Azure Document Intelligence credentials to this configuration file.

## Usage
Here’s a basic example of how you might use the package in a Laravel controller:

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BlueHex\AzureDI\AzureDI;

class DocumentController extends Controller
{
    //protected $azureDI;

    public function __construct()
    {
        $this->azureDI = $azureDI;
    }

    public function analyzeDocument(Request $request)
    {
        $file = $request->file('document');

        // Assuming the package has a method to analyze documents
        $result = AzureDI::make()->analyzeDocument($file->getPathname());

        return response()->json($result);
    }
}
```
## Routes
You can define a route to handle the document upload and analysis:

```php
use App\Http\Controllers\DocumentController;

Route::post('/analyze-document', [DocumentController::class, 'analyzeDocument']);

```

## Frontend Form
Here’s a simple HTML form to upload a document:

```
<form action="/analyze-document" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="document" required>
    <button type="submit">Analyze Document</button>
</form>
```
