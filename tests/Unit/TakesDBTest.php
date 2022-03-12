<?php

namespace Tests\Unit;

use App\Takes\TkAuthorized;
use App\Takes\TkDownloadedPDF;
use App\Takes\TkFake;
use App\Takes\TkInertia;
use App\Takes\TkJson;
use App\Takes\TkRedirectedBack;
use App\Takes\TkRedirectedRoute;
use App\Takes\TkRedirectedWith;
use App\Takes\TkTernary;
use App\Takes\TkWithCallback;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrayableOf;
use Maxonfjvipon\Elegant_Elephant\Text\TextOf;
use Modules\User\Entities\Business;
use Modules\User\Entities\User;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TakesDBTest extends TestCase
{
    /**
     * @var string $html
     */
    private string $html = "<html lang=\"ru\"><body><div>Hello world</div></body></html>";

    /**
     * @var string $permissionName
     */
    private string $permissionName = 'fake_permission';

    /**
     * @return void
     */
    public function test_authorized_take_act_without_permission()
    {
        $this->actingAs(User::fakeWithRole())
            ->expectException(AuthorizationException::class);
        (new TkAuthorized(
            $this->permissionName,
            new TkFake()
        ))->act();
    }

    /**
     * @return void
     */
    public function test_authorized_take_act_with_permission()
    {
        $user = User::fakeWithRole();
        $user->givePermissionTo(Permission::create(['name' => $this->permissionName])->name);
        $this->actingAs($user);
        $this->assertEquals(
            200,
            (new TkAuthorized(
                $this->permissionName,
                new TkFake()
            ))->act()->getStatusCode()
        );
    }

    /**
     * @throws Exception
     */
    public function test_take_downloaded_pdf_returns_response()
    {
        $this->assertInstanceOf(
            Response::class,
            (new TkDownloadedPDF($this->html))->act()
        );
    }

    /**
     * @throws Exception
     */
    public function test_take_downloaded_pdf_downloads_file()
    {
        $this->createTestResponse(
            (new TkDownloadedPDF($this->html))->act()
        )
            ->assertDownload()
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * @throws Exception
     */
    public function test_inertia_take_act_returns_inertia_response()
    {
        $this->assertInstanceOf(
            \Inertia\Response::class,
            (new TkInertia("Component", []))->act()
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_inertia_take_act_with_string_and_array()
    {
        $this->createTestResponse(
            (new TkInertia("Component", [
                'props' => 'Hello world'
            ]))->act()->toResponse(request())
        )->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Component', false)
            ->where('props', 'Hello world')
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_inertia_take_act_with_text_and_arrayable()
    {
        $this->createTestResponse(
            (new TkInertia(
                new TextOf("Component"),
                new ArrayableOf(['props' => 'Hello world'])
            ))->act()->toResponse(request())
        )->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Component', false)
            ->where('props', 'Hello world')
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_inertia_take_act_with_string_and_callable()
    {
        $this->createTestResponse(
            (new TkInertia(
                "Component",
                fn() => ['props' => 'Hello world']
            ))->act()->toResponse(request())
        )->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Component', false)
            ->where('props', 'Hello world')
        );
    }

    /**
     * @throws Exception
     */
    public function test_json_take_act_returns_json_response()
    {
        $this->assertInstanceOf(
            JsonResponse::class,
            (new TkJson([], 200, []))->act()
        );
    }

    /**
     * @throws Exception
     */
    public function test_json_take_act()
    {
        $this->createTestResponse(
            (new TkJson(
                ['data' => 'value'],
                202,
                ['test_header' => 'test_header_value']
            ))->act()
        )
            ->assertJson(fn(AssertableJson $json) => $json
                ->where('data', 'value')
            )
            ->assertHeader('test_header', 'test_header_value')
            ->assertStatus(202);
    }

    /**
     * @param string $name
     * @param string $url
     * @return void
     */
    private function setFakeRoute(string $name = "fake", string $url = "fake")
    {
        Route::name($name)->get($url, function () {
            return (new TkFake())->act();
        });
    }

    /**
     * @return void
     */
    public function test_redirected_route_take_returns_redirect_response()
    {
        $this->setFakeRoute();
        $this->assertInstanceOf(
            RedirectResponse::class,
            (new TkRedirectedRoute(
                'fake'
            ))->act()
        );
    }

    /**
     * @return void
     */
    public function test_redirected_route_take_redirect_returns_redirect_response()
    {
        $this->setFakeRoute();
        $this->assertInstanceOf(
            RedirectResponse::class,
            (new TkRedirectedRoute(
                'fake'
            ))->redirect()
        );
    }

    /**
     * @return void
     */
    public function test_redirected_route_take_redirects_to_route_with_params()
    {
        $this->setFakeRoute("fake_with_params", 'fake/{fake}');
        $this->createTestResponse(
            (new TkRedirectedRoute(
                'fake_with_params',
                2
            ))->act()
        )
            ->assertStatus(302)
            ->assertRedirect('fake/2');
    }

    /**
     * @return void
     */
    public function test_redirected_back_take_returns_redirect_response()
    {
        $this->setFakeRoute();
        $this->from(\route('fake'))
            ->assertInstanceOf(
                RedirectResponse::class,
                (new TkRedirectedBack())->act()
            );
    }

    /**
     * @return void
     */
    public function test_redirected_back_take_act()
    {
        $this->setFakeRoute();
        $this->from(\route('fake'))
            ->createTestResponse(
                (new TkRedirectedBack())->act()
            )->assertStatus(302)
            ->assertRedirect(\route('fake'));
    }

    /**
     * @return void
     */
    public function test_redirected_with_take_act_returns_redirect_response()
    {
        $this->setFakeRoute();
        $this->assertInstanceOf(
            RedirectResponse::class,
            (new TkRedirectedWith(
                'key',
                'value',
                new TkRedirectedBack()
            ))->act()
        );
    }

    /**
     * @return void
     */
    public function test_redirected_with_take_act()
    {
        $this->setFakeRoute();
        $this->from(\route('fake'))
            ->createTestResponse(
                (new TkRedirectedWith(
                    'key',
                    'value',
                    new TkRedirectedBack()
                ))->act()
            )->assertRedirect(\route('fake'))
            ->assertStatus(302)
            ->assertSessionHas('key', 'value');
    }

    /**
     * @throws Exception
     */
    public function test_ternary_take_act_returns_valid_response()
    {
        $this->assertInstanceOf(
            Response::class,
            (new TkTernary(
                true,
                new TkFake(),
                new TkRedirectedBack()
            ))->act(),
        );
    }

    /**
     * @throws Exception
     */
    public function test_ternary_take_act_can_returns_alt_take_response()
    {
        $this->assertInstanceOf(
            RedirectResponse::class,
            (new TkTernary(
                false,
                new TkFake(),
                new TkRedirectedBack()
            ))->act()
        );
    }

    /**
     * @return void
     */
    public function test_take_with_callback_act_returns_response()
    {
        $this->assertInstanceOf(
            Response::class,
            (new TkWithCallback(
                fn() => "foo",
                new TkFake()
            ))->act()
        );
    }

    /**
     * @return void
     */
    public function test_take_with_callback_act_executes_callback()
    {
        $num = 0;
        (new TkWithCallback(
            function() use (&$num) {
                $num = 5;
            },
            new TkFake()
        ))->act();
        $this->assertEquals(5, $num);
    }
}
