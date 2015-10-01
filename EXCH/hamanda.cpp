#include <iostream>       // std::cout
#include <thread>         // std::thread
#include "NetworkController.cpp"
##ifndef EXCH_H
#define EXCH_H
#endif

class exch {
	public:
		exch(){

		}
		~exch(){

		}

		NetworkInterface* netIface;

		Networktables* netTables;

		ServiceInterface* servIface;

		Scheduler* sched;
};

class ScheduleManager
{
public:
	SchedulerManager();
	~SchedulerManager();

	//The schedule uses interfaces to see to fulfiling the necessary network operations, ie. broadcast coverage, channel changes
	//These operations must be time synchronized to best ensure efficient bandwidth use and avoiding signal disruption while
	//Covering sufficient time to guarentee complete discovery within X ms

	//Operations are scheduled to within the lowest sustained average ping time between two devices. This alows for buffering 
	//to give allowance for extra required communication completion time

	//schedule responsible for dictating when the wireless channel needs to change, and when certain runnables are started
	//to process the sockets, for Broadcast in/out, link, stream 
	Schedule* masterSchedule;

	ScheduleBlock* currentSched;

	int currentTime;
	void ExecuteScheduleOperation(ScheduleBlock* opp, int time=.now(), char* sTAG);


};

class BCastReceive_ScheduleBlock : ScheduleBlock {
public:
	BCastReceive_ScheduleBock()
	: ScheduleBlock() {
		isRunning = false;
	}
	~BCastReceive_Schedule();

	

	void Run(int setInterval){
		if(!isRunning) {
			isRunning = true;
			ThreadRun(setInterval);
		}
	};
private:
	bool isRunning;

	thread* running;

	void ThreadRun(int setInterval){
		= threadManager.BroadCastRcvThread(signal, 'run', interval, Run());

		signal(if strcmp(time.now() , interval)) {
			threadManager.Interupt(funcInt, "Interval Reached");
			return 0;
		}
	};

};

class Schedule {
	//The schedule is only to maintain some idea of what is upcomming, it does not have to be implemented for all scenarios.
	//In many circumstances 
public:
	Schedule(int numBlocks){
		schedule = new *ScheduleBlock[numBlocks];
		for (int i = 0; i < sizeof(schedule); ++i)
		{
			schedule[i] = new ScheduleBlock();
		}
	}
	int periodTime;
	ScheduleBlock* schedule[];

	ScheduleBlock** RequestSchedule(char* sTAG);
	bool SetSchedule(char* sTAG, ScheduleBlock** newSched);

};



class ThreadManager {
public:

	int BroadCastRcvThread(void* signalHandler, char* opp, int interval, void* runnable){
		if(signalHandler == NULL || opp == NULL || interval == NULL == runnable == NULL) {
			printf("%s\n", err('null param'));
			return;
		}
		if(strcmp(opp, 'run')){
			bcastReceive[bcastReceive.length] = new thread(signalHandler, interval, runnable);
			bcastReceive[bcastReceive.length-1].run();
		}
	}
	ThreadManager();
	~ThreadManager();

	Thread* bcastReceive;
	Thread* bcastSend[];

	Thread* running[];

	Thread* ExchExecutors[];

	Thread* link;
	Thread* connection;


	//back end to this interface needs to manage when threads sleep and run and set signal and interupts
	void CreateThread(char* type);

	BCastThreads*  

	void InnteruptThread(char* intMessages[]);

	void TurnOnThread(thread* thread, int startTime);

	void TurnOffThread(thread* thread, int stopTime);

	void SetRunnable(thread* thread, void* function, params);


};

//thread runnables
class BroadcastReceive : runnable
{
public:
	BroadcastReceive(){
		interupted = false;
	};
	~BroadcastReceive();


	bool interupted;
	char* intReason;
	UP_Socket* rcvSock;
	char[] packet;
	void run(){
		while(!interupted){
			try{
				packet = rcvSock.asynch_receive("addr_any", PORT, set_timeout);
				PacketProcessor.enqueue(packet);
				packet = getNewBuffer();
			}
			catch(Exception e){

			}
		}
		switch(intReason){

		}
	}


};

class NetworkTables {
public:
	NetworkTables();
	~Networktables();

	DeviceByLocality* devLocal;
	DiscoveredDevices* discovDev;
	ConnectedDevices* conDev;
	DisconectedDevice* discnDev;

	Device** getLocalDeviceList();
	Device* findDevice();

	connect* ConnectToDevice(char* dTAG);
};




class NetworkInterface
{
public:
	NetworkInterface() {

	}
	~NetworkInterface();


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