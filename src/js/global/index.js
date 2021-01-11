import { burgerToogler, 
         searchToogler,
         mobileSearchToogler 
} from './tooglers.js'

export default function headerSearch() {
    new burgerToogler()
    new searchToogler()
    new mobileSearchToogler()
}
