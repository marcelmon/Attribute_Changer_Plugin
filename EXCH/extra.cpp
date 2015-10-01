	class BroadcastReceiver
	{
	public:
		BroadcastReceiver(){

			bufferIn = (char**)malloc(sizeof((char*)*MAX_BUFFER_ARRAY_SIZE));

			for (int i = 0; i < 100; ++i)
			{
				this->bufferIn[i] = (char*)malloc(sizeof(char*)*MAX_BUFFER_LENGTH);
			}

			void** thread_args =  (void**)malloc(sizeof(void*)*2);
			thread_args[0]=(void*) this->bufferIn[i];
			thread_args[1] = (void*) Exch.PacketProcessor;

			this->broadcastReceiver = *thread(BCast_ReceiveBlock, thread_args);


		};

		~BroadcastReceiver();
		
		bool is_running=false;



		thread* broadcastReceiver;

		void Stop_Running(){
			broadcastReceiver.stop();
		}

		void Start_Running() {
			broadcastReceiver.start();
		}

	};



	class BroadcastSender
	{
	public:
		BroadcastSender(arguments);
		~BroadcastSender();
		

		void sendBroadcast(char* out_string, int number_of_Packets) {
			//send packets then wait until complete
		}
	
	};

