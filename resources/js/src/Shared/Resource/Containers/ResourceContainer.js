import {RoundedCard} from "../../Cards/RoundedCard";
import {ActionsContainer} from "./ActionsContainer";
import {useStyles} from "../../../Hooks/styles.hook";
import {Container} from "./Container";

export const ResourceContainer = ({title, actions, back, children}) => {
    const {margin} = useStyles()

    return (
        <Container>
            <RoundedCard
                title={title}
                extra={back && <ActionsContainer actions={back}/>}
                style={actions && margin.bottom(16)}
            >
                {children}
            </RoundedCard>
            {actions && <ActionsContainer actions={actions}/>}
        </Container>
    )
}
