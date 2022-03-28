// import React from 'react'
// import {Button, Dropdown, Menu, Tooltip} from "antd";
// import {Link, usePage} from "@inertiajs/inertia-react";
// import {TranslationOutlined} from "@ant-design/icons";
// // import Lang from "../../../../translation/lang";
//
// export const LocaleDropdown = () => {
//     const {locales} = usePage().props
//
//     return (
//         <Dropdown key="lang-change" arrow trigger={['click']} overlay={
//             <Menu>
//                 {locales.supported.map(locale => (
//                     <Menu.Item key={locale}>
//                         <Link
//                             preserveState
//                             preserveScroll
//                             method="GET"
//                             href={locales.current_localized[locale]}
//                             only={['locales', 'pumps', 'pump', 'filter_data']}
//                         >
//                             {locale.toUpperCase()}
//                         </Link>
//                     </Menu.Item>
//                 ))}
//             </Menu>
//         }>
//             {/*<Tooltip placement="left" title={Lang.get('pages.change_locale')}>*/}
//             {/*    <Button>*/}
//             {/*        <TranslationOutlined/>*/}
//             {/*    </Button>*/}
//             {/*</Tooltip>*/}
//         </Dropdown>
//     )
// }
