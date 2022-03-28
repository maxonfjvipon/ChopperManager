import React from "react";
import {useStyles} from "../../../Hooks/styles.hook";
import {FlexRoundedCard} from "../../Cards/FlexRoundedCard";
import {ActionsContainer} from "./ActionsContainer";
import {Container} from "./Container";

export const IndexContainer = ({title, type, actions, extra, children, ...rest}) => {
    const {margin} = useStyles()

    return (
        <Container>
            {actions && <ActionsContainer actions={actions}/>}
            <FlexRoundedCard
                title={title}
                type={type}
                style={actions && margin.top(16)}
                extra={extra && <ActionsContainer actions={extra}/>}
                {...rest}
            >
                {children}
            </FlexRoundedCard>
        </Container>
    )
}
