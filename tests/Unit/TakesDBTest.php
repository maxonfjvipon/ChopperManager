<?php

namespace Tests\Unit;

use App\Takes\TkAuthorize;
use App\Takes\TkDownloadPDF;
use App\Takes\TkFake;
use App\Takes\TkInertia;
use App\Takes\TkJson;
use App\Takes\TkRedirectBack;
use App\Takes\TkRedirectToRoute;
use App\Takes\TkRedirectWith;
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
use Modules\User\Entities\User;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TakesDBTest extends TestCase
{
    private string $html = '<html lang="ru"><body><div>Hello world</div></body></html>';

    private string $permissionName = 'fake_permission';

    /**
     * @return void
     */
    public function testAuthorizedTakeActWithoutPermission()
    {
        $this->actingAs(User::fakeWithRole())
            ->expectException(AuthorizationException::class);
        (new TkAuthorize(
            $this->permissionName,
            new TkFake()
        ))->act();
    }

    /**
     * @return void
     */
    public function testAuthorizedTakeActWithPermission()
    {
        $user = User::fakeWithRole();
        $user->givePermissionTo(Permission::create(['name' => $this->permissionName])->name);
        $this->actingAs($user);
        $this->assertEquals(
            200,
            (new TkAuthorize(
                $this->permissionName,
                new TkFake()
            ))->act()->getStatusCode()
        );
    }

    /**
     * @throws Exception
     */
    public function testTakeDownloadedPdfReturnsResponse()
    {
        $this->assertInstanceOf(
            Response::class,
            (new TkDownloadPDF($this->html))->act()
        );
    }

    /**
     * @throws Exception
     */
    public function testTakeDownloadedPdfDownloadsFile()
    {
        $this->createTestResponse(
            (new TkDownloadPDF($this->html))->act()
        )
            ->assertDownload()
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * @throws Exception
     */
    public function testInertiaTakeActReturnsInertiaResponse()
    {
        $this->assertInstanceOf(
            \Inertia\Response::class,
            (new TkInertia('Component', []))->act()
        );
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testInertiaTakeActWithStringAndArray()
    {
        $this->createTestResponse(
            (new TkInertia('Component', [
                'props' => 'Hello world',
            ]))->act()->toResponse(request())
        )->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Component', false)
            ->where('props', 'Hello world')
        );
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testInertiaTakeActWithTextAndArrayable()
    {
        $this->createTestResponse(
            (new TkInertia(
                new TextOf('Component'),
                new ArrayableOf(['props' => 'Hello world'])
            ))->act()->toResponse(request())
        )->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Component', false)
            ->where('props', 'Hello world')
        );
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testInertiaTakeActWithStringAndCallable()
    {
        $this->createTestResponse(
            (new TkInertia(
                'Component',
                fn () => ['props' => 'Hello world']
            ))->act()->toResponse(request())
        )->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Component', false)
            ->where('props', 'Hello world')
        );
    }

    /**
     * @throws Exception
     */
    public function testJsonTakeActReturnsJsonResponse()
    {
        $this->assertInstanceOf(
            JsonResponse::class,
            (new TkJson([], 200, []))->act()
        );
    }

    /**
     * @throws Exception
     */
    public function testJsonTakeAct()
    {
        $this->createTestResponse(
            (new TkJson(
                ['data' => 'value'],
                202,
                ['test_header' => 'test_header_value']
            ))->act()
        )
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data', 'value')
            )
            ->assertHeader('test_header', 'test_header_value')
            ->assertStatus(202);
    }

    /**
     * @return void
     */
    private function setFakeRoute(string $name = 'fake', string $url = 'fake')
    {
        Route::name($name)->get($url, function () {
            return (new TkFake())->act();
        });
    }

    /**
     * @return void
     */
    public function testRedirectedRouteTakeReturnsRedirectResponse()
    {
        $this->setFakeRoute();
        $this->assertInstanceOf(
            RedirectResponse::class,
            (new TkRedirectToRoute(
                'fake'
            ))->act()
        );
    }

    /**
     * @return void
     */
    public function testRedirectedRouteTakeRedirectReturnsRedirectResponse()
    {
        $this->setFakeRoute();
        $this->assertInstanceOf(
            RedirectResponse::class,
            (new TkRedirectToRoute(
                'fake'
            ))->redirect()
        );
    }

    /**
     * @return void
     */
    public function testRedirectedRouteTakeRedirectsToRouteWithParams()
    {
        $this->setFakeRoute('fake_with_params', 'fake/{fake}');
        $this->createTestResponse(
            (new TkRedirectToRoute(
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
    public function testRedirectedBackTakeReturnsRedirectResponse()
    {
        $this->setFakeRoute();
        $this->from(\route('fake'))
            ->assertInstanceOf(
                RedirectResponse::class,
                (new TkRedirectBack())->act()
            );
    }

    /**
     * @return void
     */
    public function testRedirectedBackTakeAct()
    {
        $this->setFakeRoute();
        $this->from(\route('fake'))
            ->createTestResponse(
                (new TkRedirectBack())->act()
            )->assertStatus(302)
            ->assertRedirect(\route('fake'));
    }

    /**
     * @return void
     */
    public function testRedirectedWithTakeActReturnsRedirectResponse()
    {
        $this->setFakeRoute();
        $this->assertInstanceOf(
            RedirectResponse::class,
            (new TkRedirectWith(
                'key',
                'value',
                new TkRedirectBack()
            ))->act()
        );
    }

    /**
     * @return void
     */
    public function testRedirectedWithTakeAct()
    {
        $this->setFakeRoute();
        $this->from(\route('fake'))
            ->createTestResponse(
                (new TkRedirectWith(
                    'key',
                    'value',
                    new TkRedirectBack()
                ))->act()
            )->assertRedirect(\route('fake'))
            ->assertStatus(302)
            ->assertSessionHas('key', 'value');
    }

    /**
     * @throws Exception
     */
    public function testTernaryTakeActReturnsValidResponse()
    {
        $this->assertInstanceOf(
            Response::class,
            (new TkTernary(
                true,
                new TkFake(),
                new TkRedirectBack()
            ))->act(),
        );
    }

    /**
     * @throws Exception
     */
    public function testTernaryTakeActCanReturnsAltTakeResponse()
    {
        $this->assertInstanceOf(
            RedirectResponse::class,
            (new TkTernary(
                false,
                new TkFake(),
                new TkRedirectBack()
            ))->act()
        );
    }

    /**
     * @return void
     */
    public function testTakeWithCallbackActReturnsResponse()
    {
        $this->assertInstanceOf(
            Response::class,
            (new TkWithCallback(
                fn () => 'foo',
                new TkFake()
            ))->act()
        );
    }

    /**
     * @return void
     */
    public function testTakeWithCallbackActExecutesCallback()
    {
        $num = 0;
        (new TkWithCallback(
            function () use (&$num) {
                $num = 5;
            },
            new TkFake()
        ))->act();
        $this->assertEquals(5, $num);
    }
}
