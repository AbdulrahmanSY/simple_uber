<template lang="">
    <div>
        <div  class="sm:mx-auto sm:w-full sm:max-w-sm">
           <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">{{title}}</h2>
        </div>

        <div v-if="!trip.id" class="mt-8 flex justify-center font-bold ">
             <Loader></Loader>
        </div>
        <div v-else class="mt-8  ">
               <GMapMap
                  ref="gMap"
                  :center="trip.destination"
                  :zoom="14"
                  style="width : 100%; height : 300px;"/>
               <div>
                  <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Going to <strong>{{trip.destination_name}}</strong></h2>
               </div>
            <div class="flex justify-between ">
               <button
                     @click="handleDeclineTrip"
                     type="submit"   class=" rounded-md  px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline " style="background-color: black; margin-top : 15px ">
                        Decline
               </button>
               <button
                      @click="handleAcceptTrip"
                      type="submit"   class=" rounded-md  px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline " style="background-color: black; margin-top : 15px ">
                      Accept
               </button>
            </div>
        </div>
    </div>
</template>
<script setup>
import {onMounted, ref} from 'vue';
import Echo from 'laravel-echo';
import {useTripStore} from "@/stores/trip"
import pusher from 'pusher-js'
import {useLoctionStores} from '@/stores/location'
import Loader from "@/components/Loader.vue";
import router from "@/router";
import http from '../Helpers/Http';


const trip = useTripStore();
const title = ref('Waiting for ride request ......')
const gMap = ref(null)
const location = useLoctionStores()
const handleDeclineTrip = ()=>{
     trip.reset()
     title.value='Waiting for ride request ......'
}
const handleAcceptTrip = ()=>{
    console.log('Accept','{{trip.id}}')
    console.log('Accept',location.current.geometry)
     http().post('/api/trips/'+trip.id+'/accept',{
          driver_location: location.current.geometry
     }).then((response)=>{
        console.log('response',response.data.destination)
          location.$patch({
            destination:{
                    name : 'passenger',
                    geometry: JSON.parse(response.data.destination)
               }
          })
          console.log('location12',location.destination.geometry)
          router.push({
               name:'driving',
          })
     }).catch((error)=>{
        console.log('handleError ',error.message)
     })
}
onMounted(async () => {

     await location.updateCurrentUserPosition()

     const echo = new Echo({
          broadcaster: 'pusher',
          key: 'bc9be84c284630b69cc3',
          wsHost: window.location.hostname,
          wsPort: 6001,
          forceTLS: false,
          disableStats: true,
          cluster: 'mt1'
     })

     echo.channel('drivers')
         .listen('.CreateTrip', (e) => {
              console.log('Received event:', e.trip.origin);
              if (e.trip) {
                   trip.$patch({
                        origin: JSON.parse(e.trip.origin),
                        destination: JSON.parse(e.trip.destination),
                        id: e.trip.id,
                        user_id: e.trip.user_id,
                        destination_name: e.trip.destination_name,
                        is_completed:e.trip.is_completed,
                        is_started:e.trip.is_started
                   });
                   title.value = 'Ride Requested: ';
                   console.log('Trip Created: ', e.trip);
                   setTimeout(initMapDirections, 2000);
              } else {
                   console.error('Invalid event format. Missing "trip" property.');
              }
         })
})
const initMapDirections = () =>{
     gMap.value?.$mapPromise?.then((mapObject) => {
               let originPoint = new google.maps.LatLng(trip.origin)
               let destinationPoint = new google.maps.LatLng(trip.destination)
               let directionService = new google.maps.DirectionsService()
               let directionDisplay = new google.maps.DirectionsRenderer({
                    map: mapObject
               })
               console.log('originPoint',trip.origin)
               console.log('currentPoint',originPoint)
               console.log('destinationPoint',destinationPoint)
               directionService.route({
                    origin: originPoint,
                    destination: destinationPoint,
                    avoidTolls: false,
                    avoidHighways: false,
                    travelMode: google.maps.TravelMode.DRIVING
               }, (res, status) => {
                    if (status === google.maps.DirectionsStatus.OK) {
                         directionDisplay.setDirections(res)
                    } else {
                         console.log(status)
                    }
               })

     })
}

</script>
<style lang="">

</style>
