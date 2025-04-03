// State management
let cart = [];
let total = 0;
const apiKey = 'your_actual_omdb_api_key_here'; // Replace with your actual API key

// Extract drama title and genre from query parameters
const params = new URLSearchParams(window.location.search);
const title = params.get('title');
const genre = params.get('genre');

// Initialize drama details
function initializeDramaDetails() {
    if (title) {
        document.getElementById('King-the-land').textContent = title;
    }
    
    if (genre) {
        document.getElementById('drama-genre').textContent = `Genre: ${genre}`;
    }
    
    // Fetch actor details if API key is provided
    if (apiKey !== 'your_actual_omdb_api_key_here') {
        fetchActorDetails();
    }
}

// Actor details fetching
async function fetchActorDetails() {
    const dramaTitle = title || 'Mr Plankton';
    const url = `https://www.omdbapi.com/?t=${encodeURIComponent(dramaTitle)}&apikey=${apiKey}`;

    try {
        const response = await fetch(url);
        const data = await response.json();

        if (data.Response === 'True') {
            const actors = data.Actors.split(', ');
            const actorDetailsContainer = document.getElementById('actor-details');

            if (actorDetailsContainer) {
                actorDetailsContainer.innerHTML = '<h4>Actors:</h4>';
                actors.forEach(actor => {
                    const actorDiv = document.createElement('div');
                    actorDiv.classList.add('actor');
                    actorDiv.innerHTML = `
                        <h5>${actor}</h5>
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(actor)}" 
                             alt="${actor}" style="width: 100px; height: auto;">
                    `;
                    actorDetailsContainer.appendChild(actorDiv);
                });
            }
        } else {
            console.error('Error fetching actor data:', data.Error);
        }
    } catch (error) {
        console.error('Error fetching actor data:', error);
    }
}

// Payment status management
function hasPaid() {
    return localStorage.getItem("paymentCompleted") === "true";
}

function markPaymentComplete() {
    localStorage.setItem("paymentCompleted", "true");
    showNotification("Payment successful! You can now access all content.");
    closeModal('payment-modal');
}

// Episode action handling
function handleAction(buttonType, episodeLink) {
    if (hasPaid()) {
        window.open(episodeLink, "_blank");
    } else {
        openPaymentModal();
    }
}

// Cart functionality
function addToCart(episodeTitle, price) {
    const priceValue = parseFloat(price.replace('LKR ', ''));
    
    // Check for duplicate items
    if (cart.some(item => item.title === episodeTitle)) {
        showNotification('This episode is already in your cart!');
        return;
    }
    
    cart.push({
        title: episodeTitle,
        price: priceValue
    });
    
    total += priceValue;
    updateCartIcon();
    showNotification(`${episodeTitle} added to cart!`);
}

function updateCartIcon() {
    const cartIcon = document.querySelector('.cart-icon');
    if (cartIcon) {
        if (cart.length > 0) {
            cartIcon.setAttribute('data-count', cart.length);
            cartIcon.classList.add('has-items');
        } else {
            cartIcon.removeAttribute('data-count');
            cartIcon.classList.remove('has-items');
        }
    }
}

// Notification system
function showNotification(message) {
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 2000);
    }, 100);
}

// Modal management
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

function openCart() {
    const modal = document.getElementById('cart-modal');
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    
    if (!cartItems || !cartTotal) return;
    
    cartItems.innerHTML = '';
    cart.forEach((item, index) => {
        const li = document.createElement('li');
        li.innerHTML = `
            ${item.title} - LKR ${item.price.toFixed(2)}
            <button onclick="removeFromCart(${index})" class="remove-item">×</button>
        `;
        cartItems.appendChild(li);
    });
    
    cartTotal.textContent = `LKR ${total.toFixed(2)}`;
    modal.style.display = 'block';
}

function removeFromCart(index) {
    if (index >= 0 && index < cart.length) {
        total -= cart[index].price;
        cart.splice(index, 1);
        openCart();
        updateCartIcon();
    }
}

function proceedToCheckout() {
    if (cart.length === 0) {
        showNotification('Your cart is empty!');
        return;
    }
    
    closeModal('cart-modal');
    openPaymentModal();
}

function openPaymentModal() {
    const modal = document.getElementById('payment-modal');
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        amountInput.value = `LKR ${total.toFixed(2)}`;
    }
    if (modal) {
        modal.style.display = 'block';
    }
}

// Payment processing
function setupPaymentForm() {
    const form = document.getElementById('payment-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const cardNumber = document.getElementById('card-number')?.value;
        const expiryDate = document.getElementById('expiry-date')?.value;
        const cvv = document.getElementById('cvv')?.value;
        const cardholderName = document.getElementById('cardholder-name')?.value;
        
        if (!cardNumber || !expiryDate || !cvv || !cardholderName) {
            showNotification('Please fill in all payment details');
            return;
        }
        
        if (!validateCard(cardNumber, expiryDate, cvv, cardholderName)) {
            showNotification('Please check your card details!');
            return;
        }
        
        showNotification('Processing payment...');
        setTimeout(() => {
            markPaymentComplete();
            cart = [];
            total = 0;
            updateCartIcon();
        }, 2000);
    });
}

// Card validation and formatting
function validateCard(cardNumber, expiryDate, cvv, cardholderName) {
    cardNumber = cardNumber.replace(/\s/g, '');
    
    const isValidNumber = cardNumber.length === 16 && /^\d+$/.test(cardNumber);
    const isValidExpiry = /^\d{2}\/\d{2}$/.test(expiryDate);
    const isValidCvv = /^\d{3}$/.test(cvv);
    const isValidName = cardholderName.trim().length > 0;
    
    return isValidNumber && isValidExpiry && isValidCvv && isValidName;
}

function setupCardFormatting() {
    const cardNumber = document.getElementById('card-number');
    const expiryDate = document.getElementById('expiry-date');
    
    if (cardNumber) {
        cardNumber.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            value = value.replace(/\D/g, '');
            value = value.replace(/(\d{4})/g, '$1 ').trim();
            e.target.value = value;
        });
    }
    
    if (expiryDate) {
        expiryDate.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2);
            }
            e.target.value = value;
        });
    }
}

// Initialize everything when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeDramaDetails();
    setupPaymentForm();
    setupCardFormatting();
    updateCartIcon();
});

function playLocalVideo(videoPath) {
    const videoPlayer = document.getElementById('video-player');
    const videoSource = document.getElementById('video-source');

    // Update the video source with the local file path
    videoSource.src = videoPath;

    // Load the video and make the player visible
    videoPlayer.load();
    videoPlayer.hidden = false;

    // Play the video
    videoPlayer.play();
}

// Function to download a local video file
function downloadLocalVideo(videoPath) {
    const link = document.createElement('a');
    link.href = videoPath; // URL to the local file
    link.download = videoPath.split('/').pop(); // Extract the filename from the path
    link.click(); // Trigger download
}

// Function to close the player (if using the iframe-based player)
function closePlayer() {
    const playerCorner = document.getElementById('player-corner');
    playerCorner.classList.add('hidden');
}
function toggleCart() {
    const modal = document.getElementById("cart-modal");
    if (modal.style.display === "flex") {
      modal.style.display = "none"; // Close the modal if it's already open
    } else {
      modal.style.display = "flex"; // Open the modal if it's closed
    }
  }
  
function clearCart() {
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    
    // Clear all cart items
    cartItems.innerHTML = '';
  
    // Reset total price
    cartTotal.textContent = '0.00';
  
    // Optionally, clear cart data in localStorage/sessionStorage if used
    // localStorage.removeItem('cart'); // Uncomment if using localStorage
  
    alert('Cart has been cleared!');
}

function markCompleted(button) {
    const card = button.closest('.episode-card');
    const completionTag = card.querySelector('.completion-tag');
    
    // Toggle the completion status
    if (completionTag.textContent === "Not Completed") {
      completionTag.textContent = "Completed";
      completionTag.classList.add('completed'); // Change background color to green for completed
    } else {
      completionTag.textContent = "Not Completed";
      completionTag.classList.remove('completed'); // Change back to orange for not completed
    }
    
    completionTag.style.display = 'block'; // Show the completion status tag
  }

 
  
  function playVideo() {
    const videoContainer = document.getElementById('video-container'); // Get the video container
    const episodeVideo = document.getElementById('episode-video'); // Get the video element

    // Show the video container
    videoContainer.style.display = 'block';

    // Start playing the video
    episodeVideo.play();
}
