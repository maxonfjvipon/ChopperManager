import React from 'react'
import {Layout} from "antd";
import {useStyles} from "../../Hooks/styles.hook";
import {MessagesLayout} from "./MessagesLayout";

export default function Guest({children}) {
    const {Header, Content} = Layout
    const {backgroundColorWhite} = useStyles()

    return (
        <MessagesLayout>
            <Layout>
                <Header style={backgroundColorWhite}/>
                <Content style={backgroundColorWhite}>
                    {children}
                </Content>
            </Layout>
        </MessagesLayout>
    )
}
