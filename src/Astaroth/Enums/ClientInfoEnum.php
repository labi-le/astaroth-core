<?php

namespace Astaroth\Enums;

enum ClientInfoEnum: string
{
    case TEXT = "text";
    case VKPAY = "vkpay";
    case OPEN_APP = "open_app";
    case LOCATION = "location";
    case OPEN_LINK = "open_link";
    case CALLBACK = "callback";
    case INTENT_SUBSCRIBE = "intent_subscribe";
    case INTENT_UNSUBSCRIBE = "intent_unsubscribe";
}