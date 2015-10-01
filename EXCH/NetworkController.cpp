#include <sys/types.h>
#include <sys/socket.h>
#include <unistd.h>
#include <string>

using namespace std

class NetworkController
{
public:
	NetworkController() {

	}
	~NetworkController();


	Exch.Interface* exch_i;
	void SetExchInterface();


	BroadcastReceiver* bcastReceiver;
	void SetBroadcastReceiver();


	WifiConfig* wifiConfig;
	void ChangeCurrentChannel();


	void SendBroadcast();


	//Connection WILL GIVE ALL NECESSARY REPRESENTATION OF Connection
		//include the socket, the path, end device info pointer
		//also the buffer of in and out packets

		//A link is created when 2 devices each have a scheduled time
		//to perform some synchronous operation, aka data exchange
	Connection* CreateConnection(char* destinationTAG);

	class Connection
	{
	public:
		Connection();
		~Connection();
		
		socket* connectionSocket;
		char* connectedTo;

		byte schedule[4][MAX_CYCLES_PER_PERIOD];


		thread* connectionThread;
		void makeConnectionThread(socket, );

		void set_timeout();

		...can either make use of a socket 
		...or use built in funtions
		ex: send to deviceTagName [-file] filename.ext /path
			cmd [-from] [deviceTagName]

	};


	//Link IS THE COLLECTION OF Connections AT THIS->NODE IN SAME STREAM
		//Conections MUST BE !!SYNCHRONISED!!
	Link* CreateLink(char* senderTAG, char* destinationTAG, StreamMapping);

	
	//STREAM MAPPING IS INFORMATION ABOUT EXISTANT STREAMS and links
		//Already formed streams have buffer periods to allow for time overflow
		//This buffer period allows for the acceptance of variable dataflow with
		//less overhead. For long distances (large number of hops), joining data
		//streams instead of forging new ones is quicker and happens instantly
		//where streams are immediately availible.
	StreamMapping* streamMapping;

	StreamMapping* FindBestPath(string senderTAG, string receiverTAG);

	//Stream Mapping changes itself



};

class BroadcastReceiver
 {
 public:
 	BroadcastReceiver();
 	~BroadcastReceiver();

 	thread* receiverThread;
 	void receiverThread_Function();

	void receiverThread_Interface();

	void receiverThread_SignalHandler();
 

 }; 



class WifiConfigInterface
{
public:
	WifiConfigInterface(arguments);
	~WifiConfigInterface();

	void channelChanger(int newChannel);
};


//default channel = 1
class RadioControler
{
public:


	RadioControler();
	~RadioControler();
	
	void radioOn();


	WifiConfigInterface* getWifiConfig();
	bool setWifiConfig();



	//GET RAW BYTE STREAM
	pipe* getPipeToOut();
	pipe* getPipeIn();

	buffer* getInBuffer();
	buffer* getOutBuffer();

	socket* getInSocket();
	socket* getOutSocket();


	void sendOut();
	void receive();

	
};



class buffer
{
public:
	buffer();
	buffer = new char**;
	for(i:bufferSize){
		buffer[i] = new char*;
	}
	~buffer() {
		buffer=NULL;
	};

	char** buffer;

	void enqeue();
	void deqeue();
};
















void SetChannel(int i){

}




class Exch 
{
public:
	Exch();
	~Exch();


	void init(int bCastChannel, int defaultBitRate);
	

	class ServiceInterface
	{

		public:
			ServiceInterface();
			~ServiceInterface();

			void** ServiceTables();

			void registerService(service);
			void unRegisterService(service);

			void serviceOn(service);
			void serviceOff(service);

			void sendPacketToService(); //using cmd notation

	};

	class Scheduler
	{

		public:
			Scheduler();
			~Scheduler();

			void updateSchedule();
			void** Schedule();

		private:
			void setRunning();
			void interupt();

		
	};


	class NetworkInterface 
	{
		public:
			NetworkInterface();
			~NetworkInterface();

			void BroadcastReceiver();
			void BroadcastSender();

			void* NetworkTables();

			void* Connections();
			void* Links();
			void* Streams();

	};


	class NetworkTables 
	{
		public:
			NetworkTables();
			~NetworkTables();

			void** localDeviceList();
			void** localDeviceTable();

			void* accessorFunctions();
	};

	class PacketProcessor 
	{
		public:
			PacketProcessor();
			~PacketProcessor();

			void enqeue(packet);
			

		private:
			void* deqeue();
			void* processPacket(packet);
			void sendToService(packet, Service);
	};




};


























































