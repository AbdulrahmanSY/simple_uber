<template lang="">
    <div >
        <div  class="sm:mx-auto sm:w-full sm:max-w-sm pb-5">
            <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Here your trip</h2>
        </div>
      <!-- <form class=" mt-11 space-y-6" action="#" @submit.prevent="handelLogin"> -->
        <div>
          <div class="mt-3 p-0 "  >
            <GMapMap
            ref="gMap"
            :center="{lat:location.destination.geometry.lat,lng:location.destination.geometry.lng}"
            :zoom="14"
            map-type-id="terrain"
            style="width : 100%; height : 300px;"
            :options="{
                zoomControl: true,
                mapTypeControl: false,
                scaleControl: false,
                streetViewControl: false,
                rotateControl: false,
                fullscreenControl: false,
                disableDefaultUi: false
              }"
        >
        <!-- <GMapMarker :position="location.destination.geometry" style="color:black"></GMapMarker> -->

        </GMapMap>
        </div>
        <div class="flex justify-between ">
            <p class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">{{location.destination.name}}</p>

            <button
            @click="handelComfirmeTrip"
            type="submit"   class=" rounded-md  px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline " style="background-color: black; margin-top : 15px ">
                let's go
            </button>
        </div>
      <!-- </form> -->
    </div>
</div>

</template>
<script setup>
import { useLoctionStores } from '@/stores/location'
import { useTripStore } from '../stores/trip';
import { useRouter } from 'vue-router'
import { onMounted,ref } from 'vue'
import axios from 'axios';
import http from '../Helpers/Http';
import TripView from './TripView.vue';
// import  {google} from '@fawmi/vue-google-maps'
const location = useLoctionStores()
const router = useRouter()
const trip = useTripStore()
const gMap = ref(null)
console.log('content',{

    origin: {"lat":location.current.geometry.lat,"lng":location.current.geometry.lng},
    destination:{"lat":location.destination.geometry.lat,"lng":location.destination.geometry.lng},
    destination_name:location.destination.name

})
const handelComfirmeTrip =()=>{
    http().post('/api/trips',{

        origin:JSON.stringify({
        lat: location.current.geometry.lat,
        lng: location.current.geometry.lng
      }),
        destination:JSON.stringify({
        lat: location.destination.geometry.lat,
        lng: location.destination.geometry.lng
      }),
        destination_name:location.destination.name

    }).then((response)=>{
        trip.$patch(response.data)
        console.log('ok response')
        router.push({
            name:'trip',
         })
    }).catch((error)=>{
        console.log('content',{

    origin: {"lat":location.current.geometry.lat,"lng":location.current.geometry.lng},
    destination:{"lat":location.destination.geometry.lat,"lng":location.destination.geometry.lng},
    destination_name:location.destination.name

})
        console.log(error)
    })
}
onMounted(async () => {
    console.log('barear '+localStorage.getItem('token'))
    if(location.destination.name ==''){
        router.push({
            name:'location',
        })
    }

    await location.updateCurrentUserPosition()
    if (!location.current || !location.destination) {
        console.error('location.current or location.destination is undefined');

    }

    gMap.value?.$mapPromise?.then((mapObject) => {
    if (mapObject) {
        let currentPoint = new google.maps.LatLng(location.current.geometry)
        let destinationPoint = new google.maps.LatLng(location.destination.geometry)
        let directionService = new google.maps.DirectionsService()
        let directionDisplay = new google.maps.DirectionsRenderer({
            map: mapObject
        })
        console.log('currentPoint',currentPoint)
        console.log('destinationPoint',destinationPoint)
        directionService.route({
            origin: currentPoint,
            destination: destinationPoint,
            avoidTolls: false,
            avoidHighways: false,
            travelMode: google.maps.TravelMode.DRIVING
        }, (res, status) => {
            if (status === google.maps.DirectionsStatus.OK) {
                directionDisplay.setDirections(res)
            } else {
                console.log("staaaaaaaaaaaataus",status)
            }
        })
    }
})


})

</script>
<style lang="">

</style>
