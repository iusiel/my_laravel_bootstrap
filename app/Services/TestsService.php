<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Filesystem\Filesystem;

class TestsService
{
    private $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function generate()
    {
        $this->makeDirectory();

        $routes = $this->getRoutes();
        foreach ($routes as $route) {
            if (in_array("GET", $route["methods"])) {
                $this->generateGetTest($route);
            }

            if (in_array("POST", $route["methods"])) {
                $this->generatePostTest($route);
            }
        }
    }

    private function makeDirectory()
    {
        // generate directory for phpunit tests
        $path = base_path("tests/Generated");
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        // generate directory for cypress tests
        $path = base_path("cypress/e2e");
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }
        return $path;
    }

    private function getRoutes()
    {
        $routes = [];
        $routeCollection = Route::getRoutes();

        foreach ($routeCollection as $key => $route) {
            if (
                strpos($route->uri, "_ignition/") === false &&
                strpos($route->uri, "sanctum/csrf-cookie") === false
            ) {
                $uriClass = $route->uri === "/" ? "/home" : $route->uri;
                $pattern = "/[^a-zA-Z0-9]/";
                $replacement = " ";

                $routes[] = [
                    "uri" => $route->uri,
                    "className" => str_replace(
                        " ",
                        "",
                        ucwords(preg_replace($pattern, $replacement, $uriClass))
                    ),
                    "methods" => $route->methods,
                    "middleware" => $route->action["middleware"],
                ];
            }
        }

        return $routes;
    }

    private function generateGetTest($route)
    {
        $route["className"] = $route["className"] . "GetTest";
        $stubName = in_array("auth", $route["middleware"])
            ? "/stubs/test.auth.get.stub"
            : "/stubs/test.get.stub";
        $stub = $this->files->get($this->getStub($stubName));
        $stub = $this->updateContents($stub, $route);

        if (
            !$this->files->exists(
                base_path("tests/Generated/" . $route["className"] . ".php")
            )
        ) {
            $this->files->put(
                base_path("tests/Generated/" . $route["className"] . ".php"),
                $stub
            );
        }

        // create cypress test
        $cypressStub = $this->files->get(
            $this->getStub("/stubs/cypress.test.stub")
        );
        $cypressStub = $this->updateContentsForCypressTest(
            $cypressStub,
            $route
        );

        if (
            !$this->files->exists(
                base_path("cypress/e2e/" . $route["className"] . ".cy.js")
            )
        ) {
            $this->files->put(
                base_path("cypress/e2e/" . $route["className"] . ".cy.js"),
                $cypressStub
            );
        }
        // end of create cypress test
    }

    private function generatePostTest($route)
    {
        $route["className"] = $route["className"] . "PostTest";
        $stubName = in_array("auth", $route["middleware"])
            ? "/stubs/test.auth.post.stub"
            : "/stubs/test.post.stub";
        $stub = $this->files->get($this->getStub($stubName));
        $stub = $this->updateContents($stub, $route);
        if (
            !$this->files->exists(
                base_path("tests/Generated/" . $route["className"] . ".php")
            )
        ) {
            $this->files->put(
                base_path("tests/Generated/" . $route["className"] . ".php"),
                $stub
            );
        }
    }

    private function getStub($stubPath)
    {
        return $this->resolveStubPath($stubPath);
    }

    private function resolveStubPath($stub)
    {
        return file_exists($customPath = base_path(trim($stub, "/")))
            ? $customPath
            : __DIR__ . $stub;
    }

    private function updateContents($stub, $route)
    {
        $stub = str_replace("{{ namespace }}", "Tests\Generated", $stub);
        $stub = str_replace("{{ class }}", $route["className"], $stub);
        $stub = str_replace("{{ route }}", $route["uri"], $stub);
        return $stub;
    }

    private function updateContentsForCypressTest($stub, $route)
    {
        $stub = str_replace("{{ routeName }}", $route["uri"], $stub);
        $stub = str_replace("{{ route }}", url($route["uri"]), $stub);
        return $stub;
    }
}
