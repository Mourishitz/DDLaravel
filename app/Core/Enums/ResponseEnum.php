<?php

namespace App\Core\Enums;

enum ResponseEnum: string
{
    use CoreEnum;

    /*
     * HTTP Response message
     */
    case RESOURCE_NOT_FOUND = 'Resource not found.';
    case ROUTE_NOT_FOUND = 'Route not found';
    case BAD_REQUEST = 'Bad Request';
    case INTERNAL_SERVER_ERROR = 'Internal Server Error';

    /*
     * AUTH Response message
     */
    case FAILED_CREDENTIALS = 'message.response.fail.authenticate.credentials';
    case FAILED_REFRESH_TOKEN = 'message.response.fail.authenticate.refresh_token';
    case BLACKLISTED_TOKEN = 'message.response.fail.authenticate.blacklisted_token';
    case FAILED_INACTIVE_USER = 'message.response.fail.authenticate.inactive_user';
    case UNAUTHENTICATED = 'message.response.fail.authenticate.unauthenticated';
    case FAILED_LOGOUT = 'message.response.fail.authenticate.logout';
    case UNAUTHORIZED_DELETE_USER_AUTHENTICATED = 'message.response.unauthorized.delete_user_logged';
    case UNAUTHORIZED_IS_NOT_ALLOWED = 'message.response.unauthorized.is_not_allowed';
}
