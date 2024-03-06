import { ref, onMounted } from 'vue';
import axios from 'axios';

export default function useFacilities() {
    const facilitiesResponse = ref([]);

    onMounted(async () => {
        try {
            // Fetch facilities
            const facilities = await axios.get('/api/facilities');
            facilitiesResponse.value = facilities.data;
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    });

    return {
        facilitiesResponse
    };
}
