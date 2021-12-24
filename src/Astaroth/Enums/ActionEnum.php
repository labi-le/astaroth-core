<?php

namespace Astaroth\Enums;

enum ActionEnum: string
{
    case CHAT_INVITE_USER = "chat_invite_user";
    case CHAT_KICK_USER = "chat_kick_user";
    case CHAT_PHOTO_UPDATE = "chat_photo_update";
    case CHAT_PHOTO_REMOVE = "chat_photo_remove";
    case CHAT_CREATE = "chat_create";
    case CHAT_TITLE_UPDATE = "chat_title_update";
    case CHAT_PIN_MESSAGE = "chat_pin_message";
    case CHAT_UNPIN_MESSAGE = "chat_unpin_message";
    case CHAT_INVITE_USER_BY_LINK = "chat_invite_user_by_link";
    case CONVERSATION_STYLE_UPDATE = "conversation_style_update";
}