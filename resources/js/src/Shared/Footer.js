import React from "react";
import {Layout} from 'antd'

export const Footer = () => <Layout.Footer style={{textAlign: 'center'}}>
    Copyright © <a color="inherit" href="https://bpeltd.ru/">ООО "БПЕ"</a> {new Date().getFullYear()}
</Layout.Footer>
