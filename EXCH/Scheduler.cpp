#include <iostream>       // std::cout
#include <thread>         // std::thread


class Scheduler
{
public:
	Scheduler();
	~Scheduler();


	void* Packet_Processor_Enqueue(); // make class with interface 


private:

	void* Shedule_Request();//use callback structure through service interface

	void* Service_Interface();

	void* Network_Interface();

	void* Current_Schedule();

	void* Scheduler_Thread();
	
	void* Packet_Processor_Dequeue();

	//make record changes 
	void* Broadcast_Interface();


};