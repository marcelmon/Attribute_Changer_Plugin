#include <iostream>       // std::cout
#include <thread>         // std::thread


class Network_Interface
{
public:
	Network_Interface(void* pack_processor){
		Packet_Processor(pack_processor);
	};

	~Network_Interface();

	/* buffer_and_request(0)

	set in or out
	in can place into the buffer from index 0 to MAX_LEN, out will send contents that fill buffer at time of request
	only executed when the interface can run
	occurs between connection/broadcast-receive/wifi-enable, this ensures no direct interfearance

	external thread calls this interface, sends sig to network_thread, external thread yields, or allows network interface to run
	
	
	only the scheduler has access to this interface

	the scheduler sets when, what channel, and what buffers in and out to use
	the buffers can be of any object's, as set by the scheduler

	typically the packet processor is the buffer in
	it parses all packets and and processes those with known service tags 
		
		-broadcast packets: fill the corresponding service buffers with relevant data from the packet
			*can set auto response function interfaces for a service*
		
		-connection packets: 	here the scheduler has requested particular time to receive packets, and set the array to be used as a buffer
								inform the scheduler that the packet has arrived, data is ready
								the scheduler can then notify the service, and the buffer will be kept for the service until accessed (copy the buffer)
	
								the service has set a function interface and data processor to be used, i.e. a set of methods to extract the relevant info from the dataset received
								Interfacing can operate similar to a session, call response, written in any language, behave as ip, udp, service.exch, unix, ftp, http
		
		-stream packets - will be sent out again automatically, linked to a scheduled out buffer

PACKET PROCESSOR DETERMINES WHAT THE PACKET IS/DESTINATIONS (ie devices or service[if is this device])
	Exch service can be used to discover and uncover paths to devices and services

	The network interface performs the ACK signaling and any numchecking as per the requested signal type (broadcast, connection, link, stream)
	
	Other signals include: searching for, relay request(as subset of stream/link), looking_for_local
	

	signals
		--> Primary communication of commands between nodes

		acks
			--> Base Acknowledgements between connections

		broadcast
			--> Packet transmission to identify locally, also encrypted

		connection
			--> Socket schedule between two endpoints
		
		link
			--> This is a connection tuple that routes packets from a sender, to a receiver, requiging two connection hops

		stream
			--> This is the collection of local links that match any connection ends
			--> Map of local nodes with links, and respective schedules  
			
			--> consider using tcp framwork, with tcp addressing locally, groups identify via subnet, also wide area_net for long routing

		-->link/stream packets must be received and determined to be a stream packet before sending out again
	*/


	//////////////////
	void* set_buffer_and_request();

	void* Set_Packet_Protocol(broadcast, connection, stream, [udp,tcp,ip,..]);

	int Get_Current_Channel();
	bool Change_Channel();

private:
	//when a buffer is request set, expect to be imediately connected to the udp bcast out/in stream when Network_Interface runs
	//
//set

	void* signal_out_thread();
	void* signal_in_thread();

	void*  In_Buffer();
	void*  Out_Buffer();

//set
	void* Packet_Processor();
	void* NetworkController();


};