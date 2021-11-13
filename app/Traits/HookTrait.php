<?php

namespace App\Traits;
use File;
use Illuminate\Support\Str;;

trait HookTrait
{
    public function after_create($post)
    {
    	$this->hook('after_create', $post);
    }

    public function after_post($post)
    {
    	$this->hook('after_post', $post);
    }

    public function hook($method, $post)
    {
    	$addons = File::allFiles(app_path('Addons'));

    	foreach ($addons as $addon) {
            if(!Str::contains($addon->getRelativePathname(), '.php')){
                continue;
            }

    		$class_name = str_replace(['.php'], '', $addon->getRelativePathname());

    		$full_class_name = '\App\\Addons\\' . $class_name;

    		$class = new $full_class_name();

    		if(method_exists($class, $method)){

	    		$this->task('🚀 Running: ' . $class_name, function() use ($class, $method, $post){
		    		try {
			    		$class->{$method}($post);
		    			
		    		} catch (\Exception $e) {
		    			$this->error($e->getMessage());
		    		}
	    		});
    		}

    	}
    }

}