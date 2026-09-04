<section id="contact" class="contact-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="800">
            <span class="section-tag justify-content-center">Get In Touch</span>
            <h2 class="section-title">Contact Us</h2>
            <p class="section-subtitle mx-auto">Have a question or want to make a reservation? We'd love to hear from you!</p>
        </div>

        <div class="contact-grid">
            <div class="contact-form-card" data-aos="fade-right" data-aos-duration="1000">
                <h3>Send us a Message</h3>
                <form class="contact-form" action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                                <label for="name">Your Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
                                <label for="email">Your Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Your Phone">
                                <label for="phone">Your Phone</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="subject" id="subject">
                                    <option value="">Select Subject</option>
                                    <option value="reservation">Table Reservation</option>
                                    <option value="catering">Catering Services</option>
                                    <option value="feedback">Feedback</option>
                                    <option value="other">Other</option>
                                </select>
                                <label for="subject">Subject</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="message" id="message" placeholder="Your Message" style="height: 120px" required></textarea>
                                <label for="message">Your Message</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-submit">
                                Send Message <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="contact-info-card" data-aos="fade-left" data-aos-duration="1000">
                <h3>Contact Information</h3>

                <div class="contact-item">
                    <div class="contact-item-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div class="contact-item-text">
                        <h5>Address</h5>
                        @if(isset($tenant))
                            <p>{{ $tenant->address ?? 'Address not available' }}, {{ $tenant->city ?? 'Nepal' }}, {{ $tenant->country ?? 'Nepal' }}</p>
                        @else
                            <p>{{ $address ?? '123 Culinary Street, Food District, NY 10001' }}</p>
                        @endif
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-item-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div class="contact-item-text">
                        <h5>Phone</h5>
                        @if(isset($tenant))
                            <p>{{ $tenant->phone ?? 'Phone not available' }}</p>
                        @else
                            <p>{{ $contactPhone ?? '+1 (555) 123-4567' }}</p>
                        @endif
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-item-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="contact-item-text">
                        <h5>Email</h5>
                        @if(isset($tenant))
                            <p>{{ $tenant->email }}</p>
                        @else
                            <p>{{ $contactEmail ?? 'info@restaurantpro.com' }}</p>
                        @endif
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-item-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="contact-item-text">
                        <h5>Opening Hours</h5>
                        <p>Mon–Sun: 11:00 AM – 10:00 PM</p>
                    </div>
                </div>

                @if(isset($socialUrls) && count(array_filter($socialUrls ?? [])))
                <div class="contact-social">
                    @foreach($socialUrls as $platform => $url)
                        @if(!empty($url))
                            <a href="{{ $url }}" target="_blank" rel="noopener" title="{{ ucfirst($platform) }}">
                                <i class="bi bi-{{ $platform }}"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        @if(isset($location) && $location)
        <div class="contact-map" data-aos="fade-up" data-aos-duration="1000">
            {!! $location !!}
        </div>
        @endif
    </div>
</section>
