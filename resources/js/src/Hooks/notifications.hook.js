import {notification} from "antd";
import {PrimaryButton} from "../Shared/Buttons/PrimaryButton";
import React from "react";
import {Inertia} from "@inertiajs/inertia";

export const useNotifications = () => {

    const openRestoreNotification = (message, restoreRoute, buttonLabel, width = 240) => {
        notification.open({
            message,
            key: 'restore',
            btn: <PrimaryButton onClick={() => {
                Inertia.get(restoreRoute)
                notification.close('restore')
            }}>
                {buttonLabel}
            </PrimaryButton>,
            style: {
                borderRadius: 7,
                width: width,
            }
        })
    }

    return {
        openRestoreNotification,
    }

}
