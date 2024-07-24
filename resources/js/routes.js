import AllRecordsVue from "./components/AllRecords.vue"
import DashboardVue from "./components/Dashboard.vue"
import LoginVue from "./components/Login.vue"
import NewRecordVue from "./components/NewRecord.vue"
import NewVaultVue from "./components/NewVault.vue"
export default[
    {
        path:"/",
        name:"dashboard",
        component:DashboardVue
    },
    {
        path:"/signin",
        name:"signin",
        component:LoginVue
    },
    {
        path:"/newvault",
        name:"newvault",
        component:NewVaultVue
    },
    {
        path:"/records",
        name:"records",
        component:AllRecordsVue
    },
    {
        path:"/newrecord",
        name:"newrecord",
        component:NewRecordVue
    }
]