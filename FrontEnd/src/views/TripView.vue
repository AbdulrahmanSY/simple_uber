<template lang="">
    <div >
        <div  class="sm:mx-auto sm:w-full sm:max-w-sm pb-5">
            <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">{{title}}</h2>
        </div>
        <div>
          <div class="mt-3 p-0 "  >
            <GMapMap
            :center="location.current.geometry"
            :zoom="14"
            map-type-id="terrain"
            style="width : 100%; height : 300px;"
        >
        <GMapMarker v-if="location.current.geometry" :position="location.current.geometry" :icon="currentIcon"></GMapMarker>
        <GMapMarker v-if="trip.driver_location" :position="trip.driver_location" :icon="destinationIcon"></GMapMarker>


        </GMapMap>
        </div>
        <div class="flex justify-between ">
            <p class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"><strong>{{message}}</strong></p>
        </div>

    </div>
</div>

</template>
<script setup>
import {useLoctionStores} from '@/stores/location'
import {useTripStore} from '@/stores/trip'
import Echo from 'laravel-echo';
import pusher from 'pusher-js'
import { ref,onMounted } from 'vue'

const location = useLoctionStores()
const trip = useTripStore()
const title = ref('waiting on a driver...')
const message = ref('when a driver accept trip, their info will appear here...')
// const currentIcon ={
//     url:'http://openmoji.org/data/color/svg/1F698.svg',
//     scaledSize:{
//         width : 40,
//         height : 40
//     }
// }
// const driverIcon ={
//     url:'http://openmoji.org/data/color/svg/1F920.svg',
//     scaledSize:{
//         width : 40,
//         height : 40
//     }
// }

onMounted(() => {
    const echo = new Echo({
        broadcaster: 'pusher',
        key: 'bc9be84c284630b69cc3',
        wsHost: window.location.hostname,
        wsPort: 6001,
        forceTLS: false,
        disableStats: true,
        cluster: 'mt1'
    })
    console.log('trip.user_id',trip.user_id)
    echo.channel('passenger_'+trip.user_id)
        .listen('.TripAccepted', (e) => {


            trip.$patch({
                        origin: JSON.parse(e.trip.origin),
                        destination: JSON.parse(e.trip.destination),
                        id: e.trip.id,
                        user_id: e.trip.user_id,
                        destination_name: e.trip.destination_name,
                   });
            title.value = 'A driver is on the way...'
            message.value = e.trip.driver.user.name +' is coming in a ' + e.trip.driver.make + ' color is '+ e.trip.driver.color

        })
        .listen('.TripLocationUpdated',(e)=>{
            trip.$patch({
                        origin: JSON.parse(e.trip.origin),
                        destination: JSON.parse(e.trip.destination),
                        id: e.trip.id,
                        user_id: e.trip.user_id,
                        destination_name: e.trip.destination_name,
                   });        })
})
</script>

<style >

</style>
