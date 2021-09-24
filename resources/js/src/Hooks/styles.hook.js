export const useStyles = () => {
    const layout = (what, value) => {
        return {[what]: value};
    }

    const _margin = (where, value) => layout('margin' + where, value)
    const _padding = (where, value) => layout('padding' + where, value)

    const directions = {
        all: '',
        top: 'Top',
        bottom: 'Bottom',
        left: 'Left',
        right: 'Right',
    }

    const layoutObj = func => {
        return {
            all: value => func(directions.all, value),
            right: value => func(directions.right, value),
            left: value => func(directions.left, value),
            top: value => func(directions.top, value),
            bottom: value => func(directions.bottom, value)
        }
    }

    const margin = layoutObj(_margin)
    const padding = layoutObj(_padding)

    // width
    const _width = value => {
        return {width: value}
    }

    const width = value => _width(value)
    const fullWidth = _width('100%')

    // text align
    const textAlign = value => {
        return {textAlign: value}
    }

    const textAlignCenter = textAlign('center')

    // colors
    const colors = {
        white: 'white',
    }

    const backgroundColor = color => {
        return {backgroundColor: color}
    }

    // display
    const displays = {
        flex: 'flex',
    }

    const display = value => {
        return {display: value}
    }

    // justify content
    const justifies = {
        center: 'center',
        flexEnd: 'flex-end',
        spaceBetween: 'space-between'
    }

    const justifyContent = value => {
        return {justifyContent: value}
    }

    return {
        reducedAntFormItemClassName: "ant-form-item-reduced",
        reducedBottomAntFormItemClassName: "ant-form-item-reduced-bottom",

        margin,
        padding,

        width,
        fullWidth,

        minHeight: value => layout('minHeight', value),

        textAlign,
        textAlignCenter,

        borderRadius: value => layout("borderRadius", value),

        color: color => layout('color', color),

        backgroundColorWhite: backgroundColor(colors.white),

        displayFlex: display(displays.flex),

        justifyContentCenter: justifyContent(justifies.center),
        justifyContentFlexEnd: justifyContent(justifies.flexEnd),
        justifyContentSpaceBetween: justifyContent(justifies.spaceBetween)


    }
}
