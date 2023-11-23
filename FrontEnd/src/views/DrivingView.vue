
<template>
     <div  class="sm:mx-auto sm:w-full sm:max-w-sm">
          <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">{{title}}</h2>
     </div>

     <div v-if="!trip.is_completed">
        <div class="mt-3 p-0 "  >
            <GMapMap
            ref="gMap"
            :center="location.current.geometry"
            :zoom="14"
            map-type-id="terrain"
            style="width : 100%; height : 300px;"
        >
        <GMapMarker :position="location.current.geometry" :icon="currentIcon"></GMapMarker>
        <GMapMarker :position="location.destination.geometry"  :icon="destinationIcon"></GMapMarker>


        </GMapMap>
        </div>
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Go to <strong>pick up passenger</strong></h2>
       </div>
        <div v-if="trip.is_started" class="flex justify-end pt-5">
            <button
            @click.prevent="handleCompleteTrip"
             class=" rounded-md  px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline " style="background-color: black;">
                Complete trip
            </button>
        </div>
        <div v-else class="flex justify-end pt-5">
            <button
            @click.prevent="handelPassengerPackedUP"
            class=" rounded-md  px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline " style="background-color: black;">
                Passenger Pidcked Up
            </button>
        </div>
     </div>
     <div v-else>

     </div>


</template>

<script setup>
import { onUnmounted, onMounted , ref } from 'vue';
import {useLoctionStores} from '../stores/location'
import http from '../Helpers/Http';
import {useTripStore} from '../stores/trip';
import { useRouter } from 'vue-router';

const router = useRouter();
const location = useLoctionStores();
const gMap = ref(null)
const intervaleRar = ref(null)
const trip = useTripStore()
const title = ref('Driving to passenger.....')
const currentIcon ={
    url:'http://openmoji.org/data/color/svg/1F698.svg',
    scaledSize:{
        width : 40,
        height : 40
    }
}
const destinationIcon ={
    url:'http://openmoji.org/data/color/svg/1F920.svg',
    scaledSize:{
        width : 40,
        height : 40
    }
}
const handelPassengerPackedUP = ()=>{
    http().post('/api/trips/'+trip.id+'/start')
    .then((response)=>{
        title.value= 'Travaling to destination...'
        location.$patch({
            destinationIcon:{
            name:response.data.destination_name,
            geometry:JSON.parse(response.data.destination)
             }
        });
        trip.$patch(response.data)
    })
    .catch((error)=>{
        console.log('error  in strat api ',error)
    })

}
const handleCompleteTrip = ()=> {
    http().post('/api/trips/'+ trip.id +'/end')
    .then((response)=>{

        title.value = 'Trip completed successfully'

        setTimeout(() => {

            trip.$patch(response.data)

            trip.reset()

            location.reset()

            router.push({
                name:'standby'
            })
        }, 2000);


    })
    .catch((error)=>{
        console.log("end api",error);
    })

}
const updateMapBounds = (mapObject)=>{

    let origin = new google.maps.LatLng(location.current.geometry),
        destination = new google.maps.LatLng(location.destination.geometry),
        latLnBounds = new google.maps.LatLngBounds()

        latLnBounds.extend(origin)
        latLnBounds.extend(destination)
        mapObject.fitBounds(latLnBounds)
}
const broadcastDriverLocation =()=>{
    http().post('/api/trips/'+ trip.id +'/location', {
        driver_location: JSON.stringify({
        lat: location.current.geometry.lat,
        lng: location.current.geometry.lng
      }).then((response)=>{     })
        .catch((err)=>{console.log('err update location',err)})
    })

}
onMounted(() => {
    gMap.value.$mapPromise.then(async (mapObject) =>{
        await location.updateCurrentUserPosition()
        intervaleRar = setInterval(() =>{
            updateMapBounds(mapObject),5000
        })
        // update the driver location in database
        broadcastDriverLocation()

})}),
onUnmounted(() =>{
    clearInterval(intervaleRar)
    intervaleRar.value = null
})
console.log('location111111111',location.destination.geometry)

</script>

<style scoped>

</style>
