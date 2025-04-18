<?php

declare(strict_types=1);

namespace Auth0\Laravel\Controllers;

use Auth0\Laravel\Auth\Guard;
use Auth0\Laravel\{Configuration, Events};
use Auth0\Laravel\Entities\CredentialEntityContract;
use Auth0\Laravel\Events\LoginAttempting;
use Auth0\Laravel\Exceptions\ControllerException;
use Auth0\Laravel\Guards\GuardAbstract;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function sprintf;

/**
 * Controller for handling a login request.
 *
 * @api
 */
abstract class LoginControllerAbstract extends ControllerAbstract
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @param Request $request
     */
    public function __invoke(
        Request $request,
    ): Response {
        $guard = auth()->guard();

        if (! $guard instanceof GuardAbstract) {
            logger()->error(sprintf('A request implementing the `%s` controller was not routed through a Guard configured with an Auth0 driver. The incorrectly assigned Guard was: %s', self::class, $guard::class), $request->toArray());

            throw new ControllerException(ControllerException::ROUTED_USING_INCOMPATIBLE_GUARD);
        }

        $loggedIn = $guard->check() ? true : null;
        $loggedIn ??= (($guard instanceof Guard) ? $guard->find(Guard::SOURCE_SESSION) : $guard->find()) instanceof CredentialEntityContract;

        if ($loggedIn) {
            return redirect()->intended(
                config(
                    Configuration::CONFIG_NAMESPACE_ROUTES . Configuration::CONFIG_ROUTE_AFTER_LOGIN,
                    config(
                        Configuration::CONFIG_NAMESPACE_ROUTES . Configuration::CONFIG_ROUTE_INDEX,
                        '/',
                    ),
                ),
            );
        }

        session()->regenerate(true);

        Events::dispatch($event = new LoginAttempting());

        $url = $guard->sdk()->login(params: $event->parameters);

        return redirect()->away($url);
    }
}
