# Türkiye Cumhuriyeti Kimlik Numarası Doğrulama / Republic of Turkey Identity Number Verification

# Installation

To get started with Tc Kimlik Doğrulama, simply run:


````
    composer require murataygun/tckimlikdogrulama
````

# Basic Usage

**Confirm**

````
use murataygun\TcKimlik;

$data = array(
    'tcNo'          => '12345678901',
    'name'          => 'Murat',
    'surName'       => 'AYGÜN',
    'birthyear'     => '1994',
);

$confirm = TcKimlik::confirm("12345678901");
var_dump($confirm);

$confirm1 = TcKimlik::confirm($data);
var_dump($confirm);
````

**Validation**

````
use murataygun\TcKimlik;

$data = array(
    'tcNo'          => '12345678901',
    'name'          => 'Murat',
    'surName'       => 'AYGÜN',
    'birthyear'     => '1994',
);

$validate = TcKimlik::validate($data);
var_dump($validate);
````

# Laravel Service Provider
  
  Register the `murataygun\TCKimlikServiceProvider` in your `config/app.php` file:
  
````
'providers' => [
    // Other service providers...

    murataygun\TCKimlikServiceProvider::class,
],
````

If you want to change the given error message you need to add the `resources/lang/language/validation.php` file:

````
'tckimlik' => "Your error message"
````

**Extending Laravel Validator**

````
$data = array(
    'tcNo'          => '46211426258',
    'name'          => 'Murat',
    'surName'       => 'AYGÜN',
    'birthyear'     => '1994',
);


$validator = Validator::make($data, [
    'tcNo' 	 => 'required|tckimlik',
]);

$validator->after(function($validator) use ($data) {


    if (!TcKimlik::validate($data)) {
        $validator->errors()->add('formField', 'TC Kimlik Numarası doğrulanamadı.');
    }
});

if ($validator->fails())
    return $validator->errors();
````

