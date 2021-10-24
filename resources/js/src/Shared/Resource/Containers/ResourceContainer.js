import {RoundedCard} from "../../Cards/RoundedCard";
import {ActionsContainer} from "./ActionsContainer";
import {useStyles} from "../../../Hooks/styles.hook";
import {Container} from "./Container";

export const ResourceContainer = ({title, actions, back, children, ...rest}) => {
    const {margin} = useStyles()

    return (
        <Container>
            <RoundedCard
                title={title}
                extra={back && <ActionsContainer actions={back}/>}
                style={actions && margin.bottom(16)}
                {...rest}
            >
                {children}
            </RoundedCard>
            {actions && <ActionsContainer actions={actions}/>}
        </Container>
    )
}
