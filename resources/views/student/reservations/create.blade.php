@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Maak je reservering</h1>
                    <a href="{{ route('student.reservations.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-1"></i>Terug naar pakketten
                    </a>
                </div>
                
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
                @endif
                
                @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h2 class="text-lg font-semibold text-blue-800">Geselecteerd pakket: {{ $package->name }}</h2>
                    <p class="text-blue-600">{{ $package->description }}</p>
                    <div class="mt-2 flex items-center">
                        <span class="text-xl font-bold text-blue-800">€{{ number_format($package->price, 2, ',', '.') }}</span>
                        @if($package->original_price > $package->price)
                        <span class="ml-2 text-sm line-through text-blue-400">€{{ number_format($package->original_price, 2, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
                
                <form method="POST" action="{{ route('student.reservations.store') }}" id="reservationForm">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    
                    <!-- Step 1: Choose Location -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">1. Kies een locatie</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($locations as $value => $name)
                                <label class="relative border rounded-lg p-4 cursor-pointer hover:bg-blue-50 transition-colors duration-200">
                                    <input type="radio" name="location" value="{{ $value }}" class="absolute top-3 right-3 h-5 w-5" required>
                                    <div class="pl-2">
                                        <h4 class="font-medium text-gray-800 capitalize">{{ $name }}</h4>
                                        <p class="text-sm text-gray-600">Windkracht 12 locatie</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Step 2: Choose Dates from Calendar -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">2. Kies {{ $package->number_of_sessions > 1 ? $package->number_of_sessions . ' datums' : 'een datum' }}</h3>
                        
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <p class="text-yellow-700">
                                <i class="fas fa-info-circle mr-1"></i> Selecteer {{ $package->number_of_sessions }} {{ $package->number_of_sessions > 1 ? 'datums' : 'datum' }} en tijdslots voor je lessen.
                            </p>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Calendar -->
                            <div class="w-full md:w-2/3">
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <button type="button" id="prevMonth" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-1 px-3 rounded">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <h4 id="currentMonth" class="text-lg font-medium text-gray-800">{{ date('F Y') }}</h4>
                                        <button type="button" id="nextMonth" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-1 px-3 rounded">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="grid grid-cols-7 gap-1 text-center">
                                        <div class="font-medium text-gray-800 py-2">Ma</div>
                                        <div class="font-medium text-gray-800 py-2">Di</div>
                                        <div class="font-medium text-gray-800 py-2">Wo</div>
                                        <div class="font-medium text-gray-800 py-2">Do</div>
                                        <div class="font-medium text-gray-800 py-2">Vr</div>
                                        <div class="font-medium text-gray-800 py-2">Za</div>
                                        <div class="font-medium text-gray-800 py-2">Zo</div>
                                    </div>
                                    
                                    <div id="calendarDays" class="grid grid-cols-7 gap-1"></div>
                                </div>
                            </div>
                            
                            <!-- Selected Dates and Times -->
                            <div class="w-full md:w-1/3">
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-medium text-gray-800 mb-3">Geselecteerde datums en tijden</h4>
                                    
                                    <div id="selectedDatesList" class="mb-4">
                                        <p class="text-gray-500 italic">Nog geen datums geselecteerd</p>
                                    </div>
                                    
                                    <div id="dateSelectionError" class="hidden text-red-500 text-sm mb-3"></div>
                                    
                                    <div id="timeSlotSelector" class="hidden mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kies een tijd voor <span id="currentDateLabel"></span></label>
                                        <select id="timeSlot" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            @foreach($timeSlots as $slot)
                                                <option value="{{ $slot['value'] }}">{{ $slot['label'] }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" id="addTimeSlot" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-3 rounded text-sm">
                                            Tijd toevoegen
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden inputs for selected dates and times -->
                        <div id="selectedDatesContainer"></div>
                        <div id="selectedTimesContainer"></div>
                    </div>
                    
                    <!-- Step 3: Duo participant details (if applicable) -->
                    @if($package->max_participants > 1)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">3. Komt er een tweede persoon mee?</h3>
                        
                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_duo" value="1" class="h-5 w-5" onchange="toggleDuoFields(true)">
                                <span class="ml-2">Ja, ik neem iemand mee</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="radio" name="is_duo" value="0" class="h-5 w-5" checked onchange="toggleDuoFields(false)">
                                <span class="ml-2">Nee, ik kom alleen</span>
                            </label>
                        </div>
                        
                        <div id="duoFields" class="hidden border rounded-lg p-4 bg-gray-50">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="duo_name" class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
                                    <input type="text" id="duo_name" name="duo_name" value="{{ old('duo_name') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="duo_email" class="block text-sm font-medium text-gray-700 mb-1">E-mailadres</label>
                                    <input type="email" id="duo_email" name="duo_email" value="{{ old('duo_email') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="duo_phone" class="block text-sm font-medium text-gray-700 mb-1">Telefoonnummer</label>
                                    <input type="text" id="duo_phone" name="duo_phone" value="{{ old('duo_phone') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Step 4: Review and submit -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">4. Bevestig je reservering</h3>
                        
                        <div class="bg-gray-50 border rounded-lg p-4 mb-4">
                            <h4 class="font-medium text-gray-800 mb-2">Jouw gegevens</h4>
                            <p><strong>Naam:</strong> {{ Auth::user()->name }}</p>
                            <p><strong>E-mail:</strong> {{ Auth::user()->email }}</p>
                            <p><strong>Telefoon:</strong> {{ Auth::user()->student->phone ?? 'Niet ingevuld' }}</p>
                        </div>
                        
                        <div class="bg-gray-50 border rounded-lg p-4 mb-4">
                            <h4 class="font-medium text-gray-800 mb-2">Betalingsinformatie</h4>
                            <p>Na het bevestigen van je reservering ontvang je een e-mail met betalingsinstructies. De reservering is definitief na ontvangst van de betaling.</p>
                            <p class="font-medium mt-2">Totaalbedrag: €{{ number_format($package->price, 2, ',', '.') }}</p>
                        </div>
                        
                        <div class="flex justify-end mt-6">
                            <button type="submit" id="submitButton" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-300" disabled>
                                Reservering bevestigen
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Calendar data from PHP
const availableDates = @json($availableDates['available']);
const unavailableDates = @json($availableDates['unavailable']);
const requiredDates = {{ $package->number_of_sessions }};

// Current view state
let currentYear = {{ $availableDates['year'] }};
let currentMonth = {{ $availableDates['month'] }};
let selectedDates = [];
let selectedDateTimes = {};

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    // Initial calendar render
    renderCalendar(currentYear, currentMonth);
    
    // Month navigation
    document.getElementById('prevMonth').addEventListener('click', function() {
        navigateMonth(-1);
    });
    
    document.getElementById('nextMonth').addEventListener('click', function() {
        navigateMonth(1);
    });
    
    // Add time slot button
    document.getElementById('addTimeSlot').addEventListener('click', function() {
        addTimeSlotToDate();
    });
    
    // Form validation
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
        }
    });
});

// Toggle duo fields visibility
function toggleDuoFields(show) {
    const duoFields = document.getElementById('duoFields');
    if (show) {
        duoFields.classList.remove('hidden');
    } else {
        duoFields.classList.add('hidden');
    }
    validateForm();
}

// Render the calendar for a specific month
function renderCalendar(year, month) {
    const firstDay = new Date(year, month - 1, 1);
    const lastDay = new Date(year, month, 0);
    const daysInMonth = lastDay.getDate();
    
    // Update header
    const monthNames = ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];
    document.getElementById('currentMonth').textContent = `${monthNames[month - 1]} ${year}`;
    
    // Get the day of week for the first day (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
    let firstDayOfWeek = firstDay.getDay();
    // Adjust for Monday as first day (0 = Monday, ..., 6 = Sunday)
    firstDayOfWeek = firstDayOfWeek === 0 ? 6 : firstDayOfWeek - 1;
    
    const calendarDays = document.getElementById('calendarDays');
    calendarDays.innerHTML = '';
    
    // Add empty cells for days before the first of the month
    for (let i = 0; i < firstDayOfWeek; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'py-2 px-1';
        calendarDays.appendChild(emptyCell);
    }
    
    // Add cells for each day of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dateString = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const cell = document.createElement('div');
        
        // Check if this date is available
        const isAvailable = availableDates.some(date => date.date === dateString);
        const isSelected = selectedDates.includes(dateString);
        
        if (isAvailable) {
            cell.className = `py-2 px-1 text-center cursor-pointer rounded transition-colors duration-200 ${
                isSelected ? 'bg-blue-500 text-white' : 'hover:bg-blue-100'
            }`;
            cell.textContent = day;
            cell.setAttribute('data-date', dateString);
            cell.addEventListener('click', function() {
                selectDate(dateString);
            });
        } else {
            cell.className = 'py-2 px-1 text-center text-gray-400';
            cell.textContent = day;
        }
        
        calendarDays.appendChild(cell);
    }
}

// Navigate to previous/next month
function navigateMonth(direction) {
    currentMonth += direction;
    
    if (currentMonth > 12) {
        currentMonth = 1;
        currentYear++;
    } else if (currentMonth < 1) {
        currentMonth = 12;
        currentYear--;
    }
    
    renderCalendar(currentYear, currentMonth);
}

// Function to check availability for a specific date
function checkTimeAvailability(dateString) {
    const timeSlotSelector = document.getElementById('timeSlotSelector');
    const timeSlot = document.getElementById('timeSlot');
    const addTimeSlotBtn = document.getElementById('addTimeSlot');
    
    // Disable the add time slot button while loading
    if (addTimeSlotBtn) {
        addTimeSlotBtn.disabled = true;
    }
    
    // Show loading state
    timeSlot.innerHTML = '<option value="">Loading available times...</option>';
    
    // Make the AJAX request
    fetch(`{{ route('student.reservations.available-times') }}?date=${dateString}&package_id={{ $package->id }}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Received time slots:', data);
            
            // Clear the select
            timeSlot.innerHTML = '';
            
            if (data.available_times && data.available_times.length > 0) {
                // Add the available time slots
                data.available_times.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.value;
                    option.textContent = slot.label;
                    timeSlot.appendChild(option);
                });
                
                // Enable the add time slot button
                if (addTimeSlotBtn) {
                    addTimeSlotBtn.disabled = false;
                }
            } else {
                // No available times
                timeSlot.innerHTML = '<option value="">Geen beschikbare tijden voor deze datum</option>';
                
                // Keep the add time slot button disabled
                if (addTimeSlotBtn) {
                    addTimeSlotBtn.disabled = true;
                }
            }
        })
        .catch(error => {
            console.error('Error fetching time slots:', error);
            timeSlot.innerHTML = '<option value="">Error loading times</option>';
            
            // Keep the add time slot button disabled
            if (addTimeSlotBtn) {
                addTimeSlotBtn.disabled = true;
            }
        });
}

// Select a date
function selectDate(dateString) {
    const index = selectedDates.indexOf(dateString);
    
    if (index > -1) {
        // Date is already selected, unselect it
        selectedDates.splice(index, 1);
        delete selectedDateTimes[dateString];
    } else {
        // Check if we already have enough dates
        if (selectedDates.length >= requiredDates) {
            // Remove the first selected date
            const removedDate = selectedDates.shift();
            delete selectedDateTimes[removedDate];
        }
        
        // Add the new date
        selectedDates.push(dateString);
        
        // Check available times for this date
        checkTimeAvailability(dateString);
        
        // Show time slot selector for this date
        showTimeSlotSelector(dateString);
    }
    
    // Update calendar UI
    renderCalendar(currentYear, currentMonth);
    
    // Update selected dates list
    updateSelectedDatesList();
    
    // Validate form
    validateForm();
}

// Show time slot selector for a specific date
function showTimeSlotSelector(dateString) {
    const timeSlotSelector = document.getElementById('timeSlotSelector');
    const currentDateLabel = document.getElementById('currentDateLabel');
    
    // Format date for display (DD-MM-YYYY)
    const formattedDate = dateString.split('-').reverse().join('-');
    
    currentDateLabel.textContent = formattedDate;
    timeSlotSelector.setAttribute('data-date', dateString);
    timeSlotSelector.classList.remove('hidden');
}

// Add time slot to selected date
function addTimeSlotToDate() {
    const timeSlotSelector = document.getElementById('timeSlotSelector');
    const dateString = timeSlotSelector.getAttribute('data-date');
    const timeSlot = document.getElementById('timeSlot').value;
    
    // Store the date and time
    selectedDateTimes[dateString] = timeSlot;
    
    // Hide the time slot selector
    timeSlotSelector.classList.add('hidden');
    
    // Update selected dates list
    updateSelectedDatesList();
    
    // Validate form
    validateForm();
}

// Update the list of selected dates and times
function updateSelectedDatesList() {
    const selectedDatesList = document.getElementById('selectedDatesList');
    const selectedDatesContainer = document.getElementById('selectedDatesContainer');
    const selectedTimesContainer = document.getElementById('selectedTimesContainer');
    
    // Clear containers
    selectedDatesList.innerHTML = '';
    selectedDatesContainer.innerHTML = '';
    selectedTimesContainer.innerHTML = '';
    
    if (selectedDates.length === 0) {
        selectedDatesList.innerHTML = '<p class="text-gray-500 italic">Nog geen datums geselecteerd</p>';
        return;
    }
    
    // Create list of selected dates and times
    const ul = document.createElement('ul');
    ul.className = 'space-y-2';
    
    selectedDates.forEach(dateString => {
        // Create hidden input for date
        const dateInput = document.createElement('input');
        dateInput.type = 'hidden';
        dateInput.name = 'selected_dates[]';
        dateInput.value = dateString;
        selectedDatesContainer.appendChild(dateInput);
        
        // Format date for display (DD-MM-YYYY)
        const formattedDate = dateString.split('-').reverse().join('-');
        
        const li = document.createElement('li');
        li.className = 'flex justify-between items-center bg-gray-100 p-2 rounded';
        
        const dateTimeText = document.createElement('span');
        
        if (selectedDateTimes[dateString]) {
            // Create hidden input for time
            const timeInput = document.createElement('input');
            timeInput.type = 'hidden';
            timeInput.name = 'selected_times[]';
            timeInput.value = selectedDateTimes[dateString];
            selectedTimesContainer.appendChild(timeInput);
            
            dateTimeText.textContent = `${formattedDate}, ${selectedDateTimes[dateString]}`;
            dateTimeText.className = 'text-green-800';
        } else {
            dateTimeText.textContent = `${formattedDate} (kies tijd)`;
            dateTimeText.className = 'text-yellow-600';
        }
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'text-red-500 hover:text-red-700';
        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
        removeBtn.addEventListener('click', function() {
            selectDate(dateString); // This will toggle/remove the date
        });
        
        li.appendChild(dateTimeText);
        li.appendChild(removeBtn);
        ul.appendChild(li);
    });
    
    selectedDatesList.appendChild(ul);
}

// Validate the form
function validateForm() {
    const submitButton = document.getElementById('submitButton');
    const errorDiv = document.getElementById('dateSelectionError');
    
    // Check if we have the required number of dates
    if (selectedDates.length < requiredDates) {
        errorDiv.textContent = `Selecteer ${requiredDates} datum(s) voor je lessen.`;
        errorDiv.classList.remove('hidden');
        submitButton.disabled = true;
        return false;
    }
    
    // Check if all selected dates have time slots
    const allDatesHaveTimeSlots = selectedDates.every(date => selectedDateTimes[date]);
    if (!allDatesHaveTimeSlots) {
        errorDiv.textContent = `Selecteer een tijdslot voor elke gekozen datum.`;
        errorDiv.classList.remove('hidden');
        submitButton.disabled = true;
        return false;
    }
    
    // Check location
    const location = document.querySelector('input[name="location"]:checked');
    if (!location) {
        errorDiv.textContent = `Selecteer een locatie.`;
        errorDiv.classList.remove('hidden');
        submitButton.disabled = true;
        return false;
    }
    
    // Check duo fields if applicable
    const isDuo = document.querySelector('input[name="is_duo"][value="1"]:checked');
    if (isDuo) {
        const duoName = document.getElementById('duo_name').value;
        const duoEmail = document.getElementById('duo_email').value;
        const duoPhone = document.getElementById('duo_phone').value;
        
        if (!duoName || !duoEmail || !duoPhone) {
            errorDiv.textContent = `Vul alle velden in voor de duo-deelnemer.`;
            errorDiv.classList.remove('hidden');
            submitButton.disabled = true;
            return false;
        }
    }
    
    // All validations passed
    errorDiv.classList.add('hidden');
    submitButton.disabled = false;
    return true;
}
</script>
@endsection
