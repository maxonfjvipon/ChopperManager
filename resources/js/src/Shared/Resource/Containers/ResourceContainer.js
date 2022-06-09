import {RoundedCard} from "../../Cards/RoundedCard";
import {ActionsContainer} from "./ActionsContainer";
import {useStyles} from "../../../Hooks/styles.hook";
import {Container} from "./Container";

export const ResourceContainer = ({title, actions, extra, children, ...rest}) => {
    const {margin} = useStyles()

    return (
        <Container>
            <RoundedCard
                title={title}
                extra={extra && <ActionsContainer actions={extra}/>}
                style={margin.bottom(actions ? 16 : 0)}
                {...rest}
            >
                {children}
            </RoundedCard>
            {actions && <ActionsContainer actions={actions}/>}
        </Container>
    )
}
