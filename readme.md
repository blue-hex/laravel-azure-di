## Installation
First, you need to install the package via Composer. Run the following command in your terminal:

`composer require blue-hex/laravel-azure-di`

## Configuration
After installing the package, you may need to publish the configuration file. This can typically be done using the following Artisan command:

`php artisan vendor:publish --provider="BlueHex\LaravelAzureDI\LaravelAzureDIServiceProvider"`

This command will publish a configuration file named `azure-di.php` to your config directory. You should then add your Azure Document Intelligence credentials to this configuration file.

## Usage
Here’s a basic example of how you might use the package in a Laravel controller:

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BlueHex\LaravelAzureDI\Facades\AzureDI;

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
### Important Notes
- Polling
  - The Azure Document Intelligence API is asynchronous and our package will poll the API until the analysis is complete.
  - You may need to increase `max_execution_time` in your `php.ini` file to allow the package to poll the API for longer periods of time.
  - Recommended value: `max_execution_time = 300`
  - You can also use laravel's queue system to handle the polling in the background. ( need help in coming up with an instruction on thi. )
