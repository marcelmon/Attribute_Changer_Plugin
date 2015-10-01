#include "top.h"
#include "Net_Onterface.h"
#include <iostream>       // std::cout
#include <thread>         // std::thread

using namespace std

class top
{
public:
	 top(arguments);
	~top();



	/* data */
};


class Net_Interface
{


public:

	Net_Interface(){
		broadcastReceiver = new BroadcastReceiver();
		broadcastReceiver.run();

		INITIALIZING_CHANNEL_RESPONSE_FOR_EXCH_PACK_PROCESSOR();

	}

	~Net_Interface();

	int requiredBroadcastChannel;
	bool bcastReceiverRunning;
	BroadcastReceiver* broadcastReceiver;
	BroadcastSender* broadcastSender;


	void SignalHandler_Out(Signal e) {

		std::cout << e.getMessage(); //ex:"BROADCAST THREAD CRASHED"

	}





	int currentWantedChannel;

	bool SOME_REASON_NOT_TO_CHANGE_CHANNEL = false;
	void CHANNEL_FORCE_TO_CURRENT_WANTED() {
		try {
			while(!SOME_REASON_NOT_TO_CHANGE_CHANNEL) {
				if(device.getChannel != currentWantedChannel) {
					device.setChannel(currentWantedChannel);
				}
			}
		}
		catch(exception e) {

		}
	}


	int main() {
		while(true) {
			try {
				this->CHANNEL_FORCE_TO_CURRENT_WANTED();
			}
			catch(exception e){

			}
		}
	}




	void SignalHandler_In(Signal e) {

		if(e.getMessage().compare("CurrentWantedChannel Channel Has Changed")) {

			newChanel = e.getChannel();

			this->SetCurrentWantedChannel(newChanel);
			
		}
		if(e.getMessage().compare("Device Channel Has Changed From Outside")) {

			int newChannel = e.getChannel();

			this->CurrentChannelChanged(newChannel);

		}

		if(e.getMessage().compare("SOME REASON NOT TO TRY FORCING THE CHANNEL BACK")) {
			SOME_REASON_NOT_TO_CHANGE_CHANNEL = true;
		}
		if(e.getMessage().compare("NOW OKAY THE CHANNEL BACK")) {
			SOME_REASON_NOT_TO_CHANGE_CHANNEL = false;
		}
	}



	


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
		char* bufferIn[];

		int currentChannel;


		thread* broadcastReceiver;
		int requiredBroadcastChannel;


		void Stop_Running(){
			broadcastReceiver.stop();
		}

		void Start_Running() {
			broadcastReceiver.start();
		}

	};
};






void BCast_ReceiveBlock(char* buffer[], packetProcessor.Enqueue_Function) : 
{
	try{
		while(!interupted(interuption)){
			socket.receive_asynch('UDP_BCAST', 'addr_any', *buffer);
			packetProcessor.Enqueue_Function(*buffer);

			increment_buffer(buffer);
		}

	}
	catch(exception e){

	}
};


void BCast_Send_Block()
{
try{
		while(!interupted(interuption)){
			socket.send('UDP_BCAST', 'addr_any', *buffer);
			

			increment_buffer(buffer);
		}

	}
	catch(exception e){

	}
};













public class EXch_Service
{
public:
	EXch_Service(arguments);
	~EXch_Service();

	/* data */
};



public class PacketProcessor
{
public:
	PacketProcessor(arguments);
	~PacketProcessor();

	void Enqueue_Function() {

	}
	/* data */
};


 std::thread first (foo);     // spawn new thread that calls foo()
  std::thread second (bar,0);  // spawn new thread that calls bar(0)

  std::cout << "main, foo and bar now execute concurrently...\n";

  // synchronize threads:
  first.join();                // pauses until first finishes
  second.join();               // pauses until second finishes

  std::cout << "foo and bar completed.\n";

  return 0;