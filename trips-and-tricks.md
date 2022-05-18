#npx
build 
```npx mix```


docker container
```docker exec -it livewire-laravel.test-1 bash```

watch
```npx mix watch -- --watch-options-poll=1000```

watch with hot
```npx mix watch -- --watch-options-poll=1000 --hot```


test
```php artisan test```
factory
```App\Models\Question::factory()->times(10)->create();```
seed
```php artisan db:seed --class=TestSeeder
```
