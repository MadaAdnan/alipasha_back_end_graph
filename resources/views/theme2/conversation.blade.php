@extends('theme2.layouts.master')
@section('content')
  <div class="container container mb-2 mt-5">
      <div class="conversation-page">
          <div class="conversation-container">
              <!-- Sidebar with conversations list -->
              <div class="conversation-sidebar" id="conversation-sidebar">
                  <x-components.convirsation-component/>
              </div>

              <!-- Main chat area -->
              <div class="conversation-main" id="conversation-main">
                  <x-components.chat-component :community="$community??null"/>
              </div>
          </div>
      </div>
  </div>

    <style>
        #chat-toggle-btn {
            display: none !important;
        }

        .conversation-page {
            padding: 20px;
            height: calc(100vh - 100px);
            overflow: hidden;
        }

        .conversation-container {
            display: flex;
            gap: 20px;
            height: 100%;
        }

        .conversation-sidebar {
            width: 25%;
            min-width: 280px;
            height: 100%;
            overflow: hidden;
        }

        .conversation-main {
            flex: 1;
            height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 992px) {
            .conversation-sidebar {
                width: 30%;
                min-width: 250px;
            }
        }

        @media (max-width: 768px) {
            .conversation-page {
                padding: 10px;
                height: calc(100vh - 80px);
            }

            .conversation-container {
                flex-direction: column;
                gap: 10px;
            }

            .conversation-sidebar {
                width: 100%;
                height: 100%;
                min-width: auto;
                display: block;
            }

            .conversation-main {
                width: 100%;
                height: 100%;
                display: none;
                flex-direction: column;
            }

            .conversation-main.active {
                display: flex;
            }

            .conversation-sidebar.hidden {
                display: none;
            }
        }
    </style>

    <script>
        // Mobile responsive chat toggle
        if (window.innerWidth <= 768) {
            const sidebar = document.getElementById('conversation-sidebar');
            const main = document.getElementById('conversation-main');
            const categoryLinks = document.querySelectorAll('.category-item-link');

            categoryLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Show chat and hide sidebar on mobile
                    sidebar.classList.add('hidden');
                    main.classList.add('active');
                });
            });

            // Add back button functionality
            const chatHeader = document.querySelector('.chat-header');
            if (chatHeader && !document.querySelector('.chat-back-btn')) {
                const backBtn = document.createElement('button');
                backBtn.className = 'chat-back-btn';
                backBtn.innerHTML = '<i class="fas fa-arrow-right"></i>';
                backBtn.title = 'العودة إلى المحادثات';
                backBtn.addEventListener('click', function() {
                    sidebar.classList.remove('hidden');
                    main.classList.remove('active');
                });
                chatHeader.insertBefore(backBtn, chatHeader.firstChild);
            }
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('conversation-sidebar');
            const main = document.getElementById('conversation-main');

            if (window.innerWidth > 768) {
                sidebar.classList.remove('hidden');
                main.classList.remove('active');
            }
        });
    </script>
@endsection

