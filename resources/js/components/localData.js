import axios from 'axios';

const localStorageKey = 'localData'; // Replace with your key

export default async function fetchLocalData() {
    const storedData = localStorage.getItem(localStorageKey);

    if (!storedData) {
        try {
            const response = await axios.get('/admin/general-data'); // Replace with your API endpoint
            const localData = response.data;
            localStorage.setItem(localStorageKey, JSON.stringify(localData));
            return localData;
        } catch (error) {
            console.error('Error fetching data:', error);
            throw error; // Re-throw the error for handling in the component
        }
    } else {
        return JSON.parse(storedData);
    }
}