import {reactive, ref ,computed} from 'vue';
import {defineStore} from "pinia";

export const useTripStore = defineStore('trip', () => {

    const id = ref(null)
    const user_id = ref(null)
    const origin = reactive({
        lat: null,
        lng: null
    })
    const destination = reactive({
        lat: null,
        lng: null
    })
    const driver_location = reactive({
        lat: null,
        lng: null
    })
    const destination_name = ref(' ')

    const is_started= ref(false)

    const is_completed = ref(false)

    const driver = reactive({
        id:null,
        color:null,
        year:null,
        make:null,
        user:{
            name:null,
        }

    })

    const reset = ()=>{
        id.value = null
        user_id.value = null
        origin.lat.value = null
        origin.lng.value = null
        destination.lat.value = null
        destination.lng.value = null
        destination_name.value = null
    }

    return {id, user_id,origin, destination,driver,driver_location, destination_name,is_started,is_completed,reset}
})
