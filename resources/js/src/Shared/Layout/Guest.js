import React from 'react'
import {Layout} from "antd";
import {useStyles} from "../../Hooks/styles.hook";
import {ErrorLayout} from "./ErrorLayout";

export default function Guest({children}) {
    const {Header, Content} = Layout
    const {backgroundColorWhite} = useStyles()

    return (
        <ErrorLayout>
            <Layout>
                <Header style={backgroundColorWhite}/>
                <Content style={backgroundColorWhite}>
                    {children}
                </Content>
            </Layout>
        </ErrorLayout>
    )
}
