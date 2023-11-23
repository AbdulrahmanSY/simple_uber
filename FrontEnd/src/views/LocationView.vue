<template lang="">
 <div >
        <div  class="sm:mx-auto sm:w-full sm:max-w-sm pb-5">
            <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Where are you going</h2>
        </div>
      <!-- <form class=" mt-11 space-y-6" action="#" @submit.prevent="handelLogin"> -->
        <div>
          <div class="mt-3">
            <GMapAutocomplete
            placeholder="My Destination"
            @place_changed="handelLocationChanged"
            class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6"

        />

        </div>
        <div class="flex justify-end pt-5">
            <button
            @click.prevent="handelSelectLocations"
            type="submit" @submit.prevent="handelLogin"  class=" rounded-md  px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline " style="background-color: black;">
                find A Ride
            </button>
        </div>
      <!-- </form> -->
    </div>
</div>
</template>
<script setup>
import { useLoctionStores } from '@/stores/location'
import { useRouter } from 'vue-router';

const location = useLoctionStores()
const router = useRouter()
const handelLocationChanged = (e)=>{

    location.$patch({
        destination:{
            name:e.name,
            address: e.formatted_address,
            geometry:{
                lat: e.geometry.location.lat(),
                lng: e.geometry.location.lng()
            }
        }
    })
}
const handelSelectLocations = ()=>{

    if(location.destination.name ){
        router.push({
            name:'map'

        })
    }

}

</script>
<style lang="">

</style>
