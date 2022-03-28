import React from "react";
import {Layout} from 'antd'

export const Footer = () => <Layout.Footer style={{textAlign: 'center'}}>
    Pump Manager ©{new Date().getFullYear()} Created by MBS
</Layout.Footer>
