import {Card} from "antd";

export const RoundedCard = ({children, className, ...rest}) => {
    return (
        <Card className={"rounded-card " + className}  {...rest}>
            {children}
        </Card>
    )
}
