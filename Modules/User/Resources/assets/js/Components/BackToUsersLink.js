import React from 'react'
import {Link} from "@inertiajs/inertia-react";

export const BackToUsersLink = () => <Link href={route('users.index')}>{"<<Назад к пользователям"}</Link>
