import {message, notification, Space} from "antd";
import {PrimaryButton} from "../Shared/Buttons/PrimaryButton";
import React from "react";
import {Inertia} from "@inertiajs/inertia";

export const useNotifications = () => {

    const openRestoreNotification = (_message, restoreRoute, buttonLabel = "Восстановить", width = 220) => {
        message.info({
            content: <Space>
                {_message}
                <PrimaryButton onClick={() => {
                    Inertia.get(restoreRoute)
                    message.destroy('restore')
                }}>
                    {buttonLabel}
                </PrimaryButton>
            </Space>,
            key: 'restore',
            style: {
                textAlign: 'right',
                borderRadius: 5,
            },
            duration: 5,
        })
    }

    return {
        openRestoreNotification,
    }

}
